<?php


namespace App\Models\App\Recruitment\Traits\Relationship;


use App\Models\App\Applicant\Event;

trait MeetingRelationship
{
    public function event()
    {
        return $this->hasOne(Event::class, 'event_id');
    }
}