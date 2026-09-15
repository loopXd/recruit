<?php

namespace App\Models\App\Applicant\Traits\Rules;

trait AttendeeRules
{
    public function createdRules()
    {
        return [
            "event_id" => "required|exists:events,id",
            "hiring_team_id" => "required|exists:hiring_teams,id"
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
