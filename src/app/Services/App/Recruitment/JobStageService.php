<?php


namespace App\Services\App\Recruitment;

use App\Models\App\Recruitment\JobStage;
use App\Services\App\AppService;

class JobStageService extends AppService
{
    public function __construct(JobStage $jobStage)
    {
        $this->model = $jobStage;
    }
}