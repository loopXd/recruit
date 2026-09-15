<?php

namespace App\Mail\Tag;


class JobAlertTag extends Tag
{
    protected $candidate;
    protected $jobPosts;

    public function __construct($candidate, $jobPosts)
    {
        $this->candidate = $candidate;
        $this->jobPosts = $jobPosts;
    }

    function notification()
    {
        return array_merge([
            '{app_name}' => config('settings.application.company_name'),
            '{app_logo}' => config('settings.application.company_logo'),
            '{candidate_name}' => $this->candidate->fullName,
            '{job_post_card}' => $this->prepareJobPostCard(),
            '{career_page_link}' => route('show_career_page'),
        ], $this->common());
    }

    public function prepareJobPostCard(): string
    {
        $template = config('jobpoint.job_alert_job_post');
        $jobPostString = '';

        foreach ($this->jobPosts as $jobPost) {
            $jobPost->load('experienceLevel', 'location');

            $replacements = [
                '{job_post_card_badges}' => $this->prepareBadges($jobPost),
                '{name}' => $jobPost->name,
                '{name_first_letter}' => ucfirst(substr($jobPost->name, 0, 1)),
                '{vacancy_count}' => $jobPost->vacancy_count,
                '{location}' => optional($jobPost->location)->address,
                '{last_submission_date}' => $jobPost->last_submission_date,
                '{job_link}' => route('public.jobPost.show_pob_post', $jobPost->slug),
            ];

            $modifiedTemplate = str_replace(array_keys($replacements), array_values($replacements), $template);
            $jobPostString .= $modifiedTemplate;
        }

        return $jobPostString;
    }


    public function prepareBadges($jobPost): string
    {
        $badgeData = [
            ucfirst(__t($jobPost->working_arrangement)),
            $jobPost->salary,
            optional($jobPost->jobType)->name,
            optional($jobPost->department)->name,
            optional($jobPost->experienceLevel)->name,
        ];


        $badges = array_filter($badgeData);

        $formattedBadges = '';
        if (count($badges) > 3) {
            $firstChunk = array_slice($badges, 0, 3);
            $secondChunk = array_slice($badges, 3);

            $formattedBadges .= '<div style="margin: 20px 0 4px 0;">';
            foreach ($firstChunk as $badge) {
                $formattedBadges .= '<p style="display: inline-block; margin: 0; background-color: #EDF0FF; color: #636363; border-radius: 4px; font-size: 12px; padding: 0.15rem 0.75rem; margin-right: 4px;">' . $badge . '</p>';
            }
            $formattedBadges .= '</div>';

            $formattedBadges .= '<div style="margin: 4px 0 16px 0;">';
            foreach ($secondChunk as $badge) {
                $formattedBadges .= '<p style="display: inline-block; margin: 0; background-color: #EDF0FF; color: #636363; border-radius: 4px; font-size: 12px; padding: 0.15rem 0.75rem; margin-right: 4px;">' . $badge . '</p>';
            }
            $formattedBadges .= '</div>';
        }
        else {
            $formattedBadges .= '<div style="margin: 20px 0 20px 0;">';
            foreach ($badges as $badge) {
                $formattedBadges .= '<p style="display: inline-block; margin: 0; background-color: #EDF0FF; color: #636363; border-radius: 4px; font-size: 12px; padding: 0.15rem 0.75rem; margin-right: 4px;">' . $badge . '</p>';
            }
            $formattedBadges .= '</div>';
        }


        return $formattedBadges;

    }
}