<?php

namespace App\Services\App\Recruitment;

use App\Models\App\Recruitment\Stage;
use App\Services\App\AppService;

class StageService extends AppService
{
    public function __construct(Stage $stage)
    {
        $this->model = $stage;
    }
}