<?php

namespace App\Models\App\JobPost;

use App\Models\App\Applicant\JobApplicant;
use App\Models\App\AppModel;
use App\Models\App\JobPost\Traits\Rules\JobPostRules;
use App\Models\Core\Auth\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\App\JobPost\Traits\Relationship\JobPostRelationship;

class JobAlert extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'department',
        'experience_level',
        'job_type',
        'working_arrangement',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'department' => 'array',
        'experience_level' => 'array',
        'job_type' => 'array',
        'working_arrangement' => 'array',
        'user_id' => 'integer',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}