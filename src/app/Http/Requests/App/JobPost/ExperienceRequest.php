<?php
namespace App\Http\Requests\App\JobPost;

use App\Http\Requests\App\AppRequest;
use App\Models\App\JobPost\ExperienceLevel;

class ExperienceRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules( new ExperienceLevel());
    }
}