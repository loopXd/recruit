<?php

namespace App\Models\App\JobPost;

use App\Models\App\AppModel;
use App\Models\App\JobPost\Traits\Rules\JobPostRules;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\App\JobPost\Traits\Relationship\JobPostRelationship;

class JobPost extends AppModel
{
    use HasFactory, JobPostRules, JobPostRelationship, Sluggable;

    protected $fillable = [
        'company_location_id',
        'department_id',
        'job_type_id',
        'status_id',
        'stages',
        'posted_by',
        'name',
        'working_arrangement',
        'salary',
        'vacancy_count',
        'description',
        'responsibilities',
        'slug',
        'last_submission_date',
        'job_post_settings',
        'apply_form_settings',
        'is_viewable',
        'experience_level_id'
    ];

    protected $casts = [
        'company_location_id' => 'integer',
        'department_id' => 'integer',
        'job_type_id' => 'integer',
        'status_id' => 'integer',
        'posted_by' => 'integer',
        'last_submission_date' => 'datetime:Y-m-d',
        'job_post_settings' => 'object',
        'apply_form_settings' => 'object',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}