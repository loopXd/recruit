<?php


namespace App\Models\App\Recruitment\Traits\Rules;


trait MeetingRules
{
    public function createdRules()
    {
        return [
            'event_id' => 'required',
            'meeting_id' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }

}