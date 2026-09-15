<?php

namespace App\Models\App\Company;

use App\Models\App\AppModel;
use App\Models\App\Company\Traits\Rules\CompanyLocationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyLocation extends AppModel
{
    use HasFactory, CompanyLocationRules;

    protected $fillable = ['address'];

}