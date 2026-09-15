<?php

namespace App\Models\App\JobPost\Traits\Rules;


trait ExperienceRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|sometimes|string|unique:experience_levels,name',
        ];
    }

//    public function updatedRules()
//    {
//        return [
//            'name' => ['required','sometimes','string','unique:experience_levels,name'. $this->model->id],
//        ];
//    }
}