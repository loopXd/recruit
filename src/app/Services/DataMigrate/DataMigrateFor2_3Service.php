<?php

namespace App\Services\DataMigrate;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Services\App\AppService;

class DataMigrateFor2_3Service extends AppService
{
    public function migrate()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '256M');

        $this->jobPostMigrate();
        $this->jobApplicantMigrate();
        $this->jobPostActionMigrate();
        return "migrated";
    }
    public function jobPostActionMigrate() {
        $job_posts = JobPost::query()->get();
        foreach($job_posts as $job_post) {
            $job_post_apply_form_setting = (gettype($job_post->apply_form_settings) === 'string') ?
                json_decode($job_post->apply_form_settings, true) : $job_post->apply_form_settings;
            $new_settings = [];
            foreach($job_post_apply_form_setting as $key=>$value) {
                if (gettype($value) === 'object') {
                    $value = (array)$value;
                }
                $new_value = $value;
                if(isset($value['key']) && $value['key'] === 'basic_information') {
                    $new_value['actions'] = (object) [
                        "delete" => false,
                        "edit" => true,
                        "move" => false,
                        "fixed" => true,
                    ];
                }
                $new_settings[] = $new_value;
            }
            $job_post->apply_form_settings = $new_settings;
            $job_post->save();
        }
    }

    public function jobPostMigrate()
    {
        $job_posts = JobPost::query()
            ->select('id', 'apply_form_settings')
            ->with(['jobApplicants' => function ($query) {
                $query->select('id', 'job_post_id', 'apply_form_setting');
                $query->orderBy('created_at', 'desc');
            }])
            ->whereHas('jobApplicants')
            ->get();
        foreach ($job_posts as $job_post) {
            if (count($job_post->jobApplicants) === 0) continue;
            $job_post_apply_form_setting = (gettype($job_post->apply_form_settings) === 'string') ?
                json_decode($job_post->apply_form_settings, true) : $job_post->apply_form_settings;
            if (!is_array($job_post_apply_form_setting)) continue;
            if (count($job_post_apply_form_setting) === 0) continue;

            $job_applicant = $job_post->jobApplicants->first();
            $apply_form_setting = (gettype($job_applicant->apply_form_setting) === 'string') ?
                json_decode($job_applicant->apply_form_setting, true) : $job_applicant->apply_form_setting;

            if (!is_array($apply_form_setting)) continue;
            if (count($apply_form_setting) === 0) continue;
            $apply_form_setting = collect($apply_form_setting);

            $new_answers = [];
            foreach ($job_post_apply_form_setting as $setting) {
                if (gettype($setting) === 'object') {
                    $setting = (array)$setting;
                }
                $new_answer = $setting;

                if (!isset($setting['key'])) {
                    $new_answers[] = $new_answer;
                    continue;
                }
                if ($setting['key'] === 'basic_information') {
                    if (gettype($setting['actions']) === 'object') {
                        $setting['actions'] = (array)$setting['actions'];
                    }
                    $setting['actions']['fixed'] = true;
                }
                $job_applicant_setting = $apply_form_setting->where('key', $setting['key'])->first();
                if (!$job_applicant_setting) {
                    $new_answers[] = $new_answer;
                    continue;
                }
                if (isset($job_applicant_setting->actions)) {
                    $job_applicant_fields = collect($job_applicant_setting->items[0]->fields ?? []);
                } else {
                    $job_applicant_fields = collect($job_applicant_setting['fields'] ?? []);
                }
                $items = [];
                foreach ($setting['items'] as $item) {
                    if (gettype($item) === 'object') {
                        $item = (array)$item;
                    }

                    $fields = [];
                    foreach ($item['fields'] as $field) {
                        if (gettype($field) === 'object') {
                            $field = (array)$field;
                        }

                        if ($field['type'] === 'custom_field') {
                            $fields[] = $field;
                            continue;
                        }

                        if (!in_array($field['type'], ['radio', 'select', 'checkbox'])) {
                            $fields[] = $field;
                            continue;
                        }
                        if (!is_array($field['options'])) {
                            $fields[] = $field;
                            continue;
                        }
                        if (count($field['options']) > 0) {
                            $fields[] = $field;
                            continue;
                        }

                        $job_applicant_field = $job_applicant_fields->where('name', $field['title'])->first();
                        if (!$job_applicant_field) {
                            $job_applicant_field = $job_applicant_fields->where('title', $field['title'])->first();
                        }
                        if (!$job_applicant_field) {
                            $fields[] = $field;
                            continue;
                        }

                        if (gettype($job_applicant_field) === 'object') {
                            $job_applicant_field = (array)$job_applicant_field;
                        }

                        $field['options'] = $job_applicant_field['options'];
                        $fields[] = $field;
                    }
                    $item['fields'] = $fields;
                    $items[] = $item;
                }
                $new_answer['items'] = $items;
                $new_answers[] = $new_answer;
            }
            $job_post->update(['apply_form_settings' => $new_answers]);
        }
        return $job_posts;
    }

    public function jobApplicantMigrate()
    {
        $job_applicants = JobApplicant::query()->with('jobPost')->get();
        $remove_ids = [];
        $answers = ApplicationAnswer::query()->get();
        foreach ($job_applicants as $job_applicant) {
            $new_answers = [];
            $new_attachments = [];
            $applicant_phone = '';
            $apply_form_setting = (gettype($job_applicant->apply_form_setting) === 'string') ?
                json_decode($job_applicant->apply_form_setting, true) : $job_applicant->apply_form_setting;

            if (!is_array($apply_form_setting)) continue;
            if (count($apply_form_setting) === 0) continue;

            $first_setting = $apply_form_setting[0];
            if (gettype($first_setting) === 'object') {
                $first_setting = (array)$first_setting;
            }
            if (isset($first_setting['actions'])) continue;
            $job_post_apply_form_setting = (gettype($job_applicant->jobPost->apply_form_settings) === 'string') ?
                json_decode($job_applicant->jobPost->apply_form_settings, true) : $job_applicant->jobPost->apply_form_settings;

            $applicant_answers = $answers->where('job_applicant_id', $job_applicant->id);
            foreach ($job_post_apply_form_setting as $setting) {
                if (gettype($setting) === 'object') {
                    $setting = (array)$setting;
                }
                $new_answer = $setting;
                $items = [];
                foreach ($setting['items'] as $item) {
                    if (gettype($item) === 'object') {
                        $item = (array)$item;
                    }

                    $fields = [];
                    foreach ($item['fields'] as $field) {
                        if (gettype($field) === 'object') {
                            $field = (array)$field;
                        }
                        $question_key = $this->nameGen($field['title']);
                        $answer = $applicant_answers->where('question', $question_key)->first();
                        $value = '';
                        if ($answer) {
                            $remove_ids[] = $answer->id;
                            if ($answer->attachment) {
                                $new_attachments[] = $answer->attachment;
                            } else {
                                // check answer type wise. reverse engineering.
                                if (in_array($field['type'], ['text', 'email', 'number', 'textarea', 'tel-input'])) {
                                    $value = $answer->answer;
                                } else if (in_array($field['type'], ['radio', 'select'])) {
                                    $answer_key = '';
                                    foreach ($field['options'] as $option) {
                                        if ($this->nameGen($option) === $answer->answer) {
                                            $answer_key = $option;
                                            break;
                                        }
                                    }
                                    if (!$answer_key) {
                                        $answer_key = $answer->answer;
                                    }
                                    $value = $answer_key;
                                } else if (in_array($field['type'], ['checkbox'])) {
                                    $possible_answer = $answer->answer;
                                    $possible_answer_key_array = explode(',', $possible_answer);
                                    $answer_key_array = [];
                                    foreach ($field['options'] as $option) {
                                        if (in_array($this->nameGen($option), $possible_answer_key_array)) {
                                            $answer_key_array[] = $option;
                                        }
                                    }
                                    if (count($answer_key_array) === 0) {
                                        $answer_key_array = $answer->answer;
                                    }
                                    $value = $answer_key_array;
                                } else {
                                    $value = $answer->answer;
                                }
                                if ($question_key === 'phone') $applicant_phone = $answer->answer;
                            }
                        }
                        $field['value'] = $value;
                        $inner_fields = [];
                        if (isset($field['fields'])) {
                            foreach ($field['fields'] as $inner_field) {
                                if (gettype($inner_field) === 'object') {
                                    $inner_field = (array)$inner_field;
                                }
                                $inner_question_key = $this->nameGen($field['title']);
                                $inner_answer = $applicant_answers->where('question', $inner_question_key)->first();
                                $inner_value = '';
                                if ($inner_answer) {
                                    $remove_ids[] = $inner_answer->id;
                                    if ($inner_answer->attachment) {
                                        $new_attachments[] = $inner_answer->attachment;
                                    } else {
                                        if (in_array($inner_field['type'], ['text', 'email', 'number', 'textarea', 'tel-input'])) {
                                            $inner_value = $inner_answer->answer;
                                        } else if (in_array($inner_field['type'], ['radio', 'select'])) {
                                            $answer_key = '';
                                            foreach ($inner_field['options'] as $option) {
                                                if ($this->nameGen($option) === $inner_answer->answer) {
                                                    $answer_key = $option;
                                                    break;
                                                }
                                            }
                                            if (!$answer_key) {
                                                $answer_key = $inner_answer->answer;
                                            }
                                            $inner_value = $answer_key;
                                        } else if (in_array($inner_field['type'], ['checkbox'])) {
                                            $possible_answer = $inner_answer->answer;
                                            $possible_answer_key_array = explode(',', $possible_answer);
                                            $answer_key_array = [];
                                            foreach ($field['options'] as $option) {
                                                if (in_array($this->nameGen($option), $possible_answer_key_array)) {
                                                    $answer_key_array[] = $option;
                                                }
                                            }
                                            if (count($answer_key_array) === 0) {
                                                $answer_key_array = $inner_answer->answer;
                                            }
                                            $inner_value = $answer_key_array;
                                        } else {
                                            $inner_value = $inner_answer->answer;
                                        }
                                        if ($question_key === 'phone') $applicant_phone = $inner_answer->answer;
                                    }
                                }
                                $inner_field['value'] = $inner_value;
                                $inner_fields[] = $inner_field;
                            }
                        }
                        $field['fields'] = $inner_fields;
                        $fields[] = $field;
                    }
                    $item['fields'] = $fields;
                    $items[] = $item;
                }
                $new_answer['items'] = $items;
                $new_answers[] = $new_answer;
            }
            ApplicationAnswer::query()->insert([
                'job_applicant_id' => $job_applicant->id,
                'question' => NULL,
                'answer' => json_encode($new_answers),
                'attachment' => NULL,
            ]);
            foreach ($new_attachments as $new_attachment) {
                $attachment_array = explode('/', $new_attachment);
                ApplicationAnswer::query()->insert([
                    'job_applicant_id' => $job_applicant->id,
                    'question' => $attachment_array[count($attachment_array) - 1] ?? $new_attachment,
                    'answer' => NULL,
                    'attachment' => $new_attachment,
                ]);
            }
            $job_applicant->update(['apply_form_setting' => $new_answers]);
            if ($applicant_phone) {
                Applicant::query()->where('id', $job_applicant->applicant_id)->update([
                    'phone' => $applicant_phone,
                ]);
            }
        }
        ApplicationAnswer::query()->whereIn('id', $remove_ids)->delete();
    }

    public function camelToSnakeCase($text)
    {
        $snakeCase = preg_replace_callback(
            '/(.)([A-Z][a-z]+)/',
            function ($matches) {
                return $matches[1] . '_' . strtolower($matches[2]);
            },
            $text
        );

        return preg_replace_callback(
            '/([a-z0-9])([A-Z])/',
            function ($matches) {
                return $matches[1] . '_' . strtolower($matches[2]);
            },
            $snakeCase
        );
    }

    public function nameGen($name)
    {// Convert to lowercase and replace apostrophes with an empty string
        $withoutApostrophes = str_replace("'", '', $name);
        $camelToSnake = $this->camelToSnakeCase($withoutApostrophes);

        // Replace non-alphanumeric characters with underscores
        $withoutSpecialChars = preg_replace('/[^A-Za-z0-9]+/', '_', $camelToSnake);

        // Trim underscores from the beginning and end
        $trimmed = trim($withoutSpecialChars, '_');


        // Replace consecutive underscores with a single underscore
        $normalized = preg_replace('/_+/', '_', $trimmed);

        $lowercase = strtolower($normalized);

        return $lowercase;
    }
}