<?php


namespace App\Models\App\Recruitment\Traits\Relationship;

use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;

trait JobStageRelationship
{
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function jobApplicants()
    {
        return $this->hasMany(JobApplicant::class, 'current_stage_id');
    }

    public function jobApplicantCount()
    {
        return $this->jobApplicants()
            ->selectRaw(' current_stage_id, count(current_stage_id) as count')
            ->groupBy('current_stage_id');
    }
}