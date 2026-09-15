<?php

namespace App\Models\App\JobPost\Traits\Relationship;

use App\Models\App\JobPost\JobPost;

trait JobApplicationSettingRelationship
{
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
}
