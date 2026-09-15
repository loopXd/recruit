<?php

namespace App\Services\App\JobPost;

use App\Models\App\JobPost\ExperienceLevel;
use App\Services\App\AppService;

class ExperienceService extends AppService
{
    public function __construct(ExperienceLevel $experienceLevel)
    {
        $this->model = $experienceLevel;
    }


    public function update()
    {
        $this->model->update($this->getAttrs());
    }

    public function validations(): self
    {
        validator($this->getAttrs(), [
            'name' => ['required','sometimes','string','unique:experience_levels,name,'. $this->model->id],
        ])->validate();

        return $this;
    }
}