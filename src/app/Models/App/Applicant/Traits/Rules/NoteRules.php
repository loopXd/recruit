<?php


namespace App\Models\App\Applicant\Traits\Rules;


trait NoteRules
{
    public function createdRules()
    {
        return [
            'job_applicant_id' => 'required|exists:job_applicants,id',
            'description' => 'required|string',
        ];
    }

    public function updatedRules()
    {
        return [
            'description' => 'required|string',
        ];
    }

}