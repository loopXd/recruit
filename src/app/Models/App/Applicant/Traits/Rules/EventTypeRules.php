<?php

namespace App\Models\App\Applicant\Traits\Rules;


trait EventTypeRules
{
    public function createdRules()
    {
        return [
            "name" => "required|string"
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
