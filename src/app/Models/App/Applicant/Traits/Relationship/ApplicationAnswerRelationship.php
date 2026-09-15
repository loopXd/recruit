<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\JobApplicant;

trait ApplicationAnswerRelationship
{
    public function jobApplicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id');
    }
}