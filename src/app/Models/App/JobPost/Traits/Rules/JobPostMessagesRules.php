<?php

namespace App\Models\App\JobPost\Traits\Rules;

trait JobPostMessagesRules
{
    public function createdRules()
    {
        return [
            'job_post_id' => 'required|exists:job_posts,id',
            'applicant_id'=> 'required',
            'email_message_id'=> 'required',
        ];
    }

    public function updatedRules()
    {
        return [
            'job_post_id' => 'required|exists:job_posts,id',
            'applicant_id'=> 'required',
            'email_message_id'=> 'required',
        ];
    }
}
