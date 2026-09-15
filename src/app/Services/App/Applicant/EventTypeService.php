<?php

namespace App\Services\App\Applicant;

use App\Models\App\Applicant\EventType;
use App\Services\App\AppService;

class EventTypeService extends AppService
{
    public function __construct(EventType $eventType)
    {
        $this->model = $eventType;
    }
}
