<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Models\App\Applicant\Event;
use App\Models\App\Applicant\Note;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\App\Recruitment\JobStage;
use App\Models\Core\File\File;
use App\Models\Core\Status;

trait JobApplicantRelationship
{
    public function appliedBy()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function currentStage()
    {
        return $this->belongsTo(JobStage::class, 'current_stage_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function answers()
    {
        return $this->hasMany(ApplicationAnswer::class, 'job_applicant_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'job_applicant_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'job_applicant_id');
    }

    public function recruiters()
    {
        return $this->hasMany(HiringTeam::class, 'job_post_id', 'job_post_id');
    }
}