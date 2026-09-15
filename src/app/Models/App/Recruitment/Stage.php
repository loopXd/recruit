<?php

namespace App\Models\App\Recruitment;

use App\Models\App\AppModel;
use App\Models\App\Recruitment\Traits\Rules\StageRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stage extends AppModel
{
    use HasFactory, StageRules;

    protected $fillable = ['name'];

    protected $casts = [
    ];
}
