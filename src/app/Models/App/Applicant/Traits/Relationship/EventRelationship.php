<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\Attendee;
use App\Models\App\Applicant\EventType;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Recruitment\Meeting;

trait EventRelationship
{
    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function jobApplicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'event_id');
    }
    public function meeting()
    {
        return $this->hasOne(Meeting::class);
    }
}
