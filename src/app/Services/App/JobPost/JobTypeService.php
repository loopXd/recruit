<?php

namespace App\Services\App\JobPost;

use App\Models\App\JobPost\JobType;
use App\Services\App\AppService;

class JobTypeService extends AppService
{
    public function __construct(JobType $jobType)
    {
        $this->model = $jobType;
    }
}