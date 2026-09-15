<?php

namespace App\Http\Requests\App\GlobalModules;

use App\Http\Requests\App\AppRequest;
// use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormRequest extends AppRequest
{
    public function rules()
    {
        return [
            'application_form' => 'required|array',
        ];
    }
}
