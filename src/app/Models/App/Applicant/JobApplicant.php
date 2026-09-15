<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\JobApplicantRelationship;
use App\Models\App\Applicant\Traits\Rules\JobApplicantRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplicant extends AppModel
{
    use HasFactory, JobApplicantRules, JobApplicantRelationship;

    protected $fillable = ['applicant_id', 'review', 'disqualification_reason',
        'job_post_id', 'slug',
        'current_stage_id', 'status_id', 'apply_form_setting'];

    protected $casts = [
        'applicant_id' => 'integer',
        'job_post_id' => 'integer',
        'current_stage_id' => 'integer',
        'status_id' => 'integer',
        'review' => 'string',
        'apply_form_setting' => 'object',
    ];
}
