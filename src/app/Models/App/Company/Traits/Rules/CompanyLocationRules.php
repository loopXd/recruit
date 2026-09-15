<?php


namespace App\Models\App\Company\Traits\Rules;


trait CompanyLocationRules
{
    public function createdRules()
    {
        return [
            'address' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}