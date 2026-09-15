<?php

namespace App\Models\App\Recruitment;

use App\Models\App\AppModel;
use App\Models\App\Recruitment\Traits\Relationship\HiringTeamRelationship;
use App\Models\App\Recruitment\Traits\Rules\HiringTeamRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HiringTeam extends AppModel
{
    use HasFactory, HiringTeamRules, HiringTeamRelationship;

    protected $fillable = ['job_post_id', 'recruiter_id'];

    protected $casts = [
        'job_post_id' => 'integer',
        'recruiter_id' => 'integer'
    ];
}