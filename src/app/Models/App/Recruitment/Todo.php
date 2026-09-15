<?php

namespace App\Models\App\Recruitment;

use App\Models\App\AppModel;
use App\Models\App\Recruitment\Traits\Relationship\TodoRelationship;
use App\Models\App\Recruitment\Traits\Rules\TodoRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends AppModel
{
    use HasFactory, TodoRules, TodoRelationship;

    protected $fillable = [
        'status_id',
        'user_id',
        'name',
    ];

    protected $casts = [
        'status_id' => 'int',
        'user_id' => 'int',
    ];
}