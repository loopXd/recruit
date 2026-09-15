<?php

namespace App\Models\App\Recruitment;

use App\Models\App\AppModel;
use App\Models\App\Recruitment\Traits\Relationship\JobStageRelationship;
use App\Models\App\Recruitment\Traits\Rules\JobStageRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobStage extends AppModel
{
    use HasFactory, JobStageRules, JobStageRelationship;

    protected $fillable = ['name', 'job_post_id'];

    protected $casts = [
        'job_post_id' => 'int',
    ];
}