<?php
namespace App\Http\Requests\App\JobPost;

use App\Http\Requests\App\AppRequest;
use App\Models\App\JobPost\JobType;

class JobTypeRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules( new JobType());
    }
}