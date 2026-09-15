<?php


namespace App\Http\Requests\App\Recruitment;


use App\Models\App\Recruitment\Todo;
use App\Services\App\AppService;

class TodoRequest extends AppService
{
    public function rules()
    {
        return $this->initRules( new Todo());
    }

}