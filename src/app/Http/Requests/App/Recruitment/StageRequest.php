<?php


namespace App\Http\Requests\App\Recruitment;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Recruitment\Stage;

class StageRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules(new Stage());
    }

}