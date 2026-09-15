<?php

namespace App\Models\App\JobPost\Traits\Relationship;

use App\Models\App\JobPost\JobPost;

trait JobTypeRelationship
{
    public function jobs()
    {
        return $this->hasMany(JobPost::class, 'applicant_id');
    }
    public function jobPost()
    {
        return $this->hasMany(JobPost::class, 'job_type_id');
    }
}