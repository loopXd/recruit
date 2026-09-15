<?php

namespace App\Http\Requests\App\JobPost;

use App\Http\Requests\App\AppRequest;
use App\Models\App\JobPost\JobPost;

class JobAlertRequest extends AppRequest
{

    public function rules()
    {
        return [
            'department' => 'nullable|array',
            'experience_level' => 'nullable|array',
            'job_type' => 'nullable|array',
            'working_arrangement' => 'nullable|array'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->checkIfAllowed()) {
                $validator->errors()->add('department', 'At least one field must be selected');
            }
        });
    }

    public function checkIfAllowed(): bool
    {
        if(!$this->department && !$this->experience_level && !$this->job_type && !$this->working_arrangement) return true;
        return false;
    }
}
