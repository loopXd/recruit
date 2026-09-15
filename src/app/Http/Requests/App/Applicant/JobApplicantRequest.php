<?php

namespace App\Http\Requests\App\Applicant;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Applicant\JobApplicant;

class JobApplicantRequest extends AppRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->initRules( new JobApplicant());
    }
}
