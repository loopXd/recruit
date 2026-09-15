<?php


namespace App\Models\App\Recruitment\Traits\Relationship;

use App\Models\App\Applicant\Attendee;
use App\Models\App\JobPost\JobPost;
use App\Models\Core\Auth\User;

trait HiringTeamRelationship
{
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'hiring_team_id');
    }
}