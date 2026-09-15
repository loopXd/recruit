<?php


namespace App\Models\App\Recruitment\Traits\Rules;


trait StageRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|unique:stages',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}