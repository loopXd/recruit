<?php

namespace App\Models\App\Applicant\Traits\Rules;

use Illuminate\Validation\Rule;

trait ApplicantRules
{
    public function createdRules()
    {
        return [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|email|unique:applicants",
            "gender" => ['nullable', Rule::in(['male', 'female', 'other'])],
            "phone" => ['required', 'string'],
            "date_of_birth" => "nullable|date|date_format:Y-m-d",
            "job_post_id" => "required|exists:job_posts,id"
        ];
    }

    public function updatedRules()
    {
        return [
            "first_name" => "nullable|string",
            "last_name" => "nullable|string",
            "email" => "nullable|email|unique:applicants",
            "gender" => ['nullable', Rule::in(['male', 'female', 'other'])],
            "phone" => ['nullable', 'string'],
            "date_of_birth" => "nullable|date|date_format:Y-m-d"
        ];
    }
}
