<?php

namespace App\Services\App\JobPost;

use App\Models\App\JobPost\JobPost;
use App\Services\App\AppService;

class JobPostService extends AppService
{
    public function __construct(JobPost $jobPost)
    {
        $this->model = $jobPost;
    }
}
