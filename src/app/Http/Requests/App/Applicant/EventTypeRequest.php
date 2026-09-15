<?php

namespace App\Http\Requests\App\Applicant;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Applicant\EventType;

class EventTypeRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules( new EventType());
    }
}
