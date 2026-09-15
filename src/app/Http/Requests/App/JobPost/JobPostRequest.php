<?php

namespace App\Http\Requests\App\JobPost;

use App\Http\Requests\App\AppRequest;
use App\Models\App\JobPost\JobPost;

class JobPostRequest extends AppRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->initRules( new JobPost());
    }
}
