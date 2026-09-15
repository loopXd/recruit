<?php

namespace App\Models\App\JobPost\Traits\Rules;


trait JobTypeRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|sometimes|string',
            'brief' => 'nullable|string',
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|sometimes|string',
            'brief' => 'nullable|string',
        ];
    }
}