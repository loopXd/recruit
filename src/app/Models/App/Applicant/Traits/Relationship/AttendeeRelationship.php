<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\Event;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\Core\Auth\User;

trait AttendeeRelationship
{
    public function hiringTeam()
    {
        return $this->belongsTo(HiringTeam::class, 'hiring_team_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
