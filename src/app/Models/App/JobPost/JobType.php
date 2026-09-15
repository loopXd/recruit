<?php

namespace App\Models\App\JobPost;

use App\Models\App\AppModel;
use App\Models\App\JobPost\Traits\Relationship\JobTypeRelationship;
use App\Models\App\JobPost\Traits\Rules\JobTypeRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobType extends AppModel
{
    use HasFactory, JobTypeRules, JobTypeRelationship;

    protected $fillable = ['name', 'brief'];
}