<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\Event;

trait EventTypeRelationship
{
    public function events()
    {
        return $this->hasMany(Event::class, 'event_type_id');
    }
}