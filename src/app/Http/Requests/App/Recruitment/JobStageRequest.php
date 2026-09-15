<?php


namespace App\Http\Requests\App\Recruitment;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Recruitment\JobStage;

class JobStageRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules(new JobStage());
    }

}