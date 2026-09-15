<?php

namespace App\Models\App\Company;

use App\Models\App\AppModel;
use App\Models\App\Company\Traits\Rules\DepartmentRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends AppModel
{
    use HasFactory, DepartmentRules;

    protected $fillable = ['name'];
}