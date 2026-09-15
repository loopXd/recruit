<?php

namespace App\Models\App\JobPost;

use App\Models\App\Applicant\Traits\Relationship\JobApplicantRelationShip;
use App\Models\App\AppModel;
use App\Models\App\JobPost\Traits\Rules\JobApplicationSettingRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplicationSetting extends AppModel
{
    use HasFactory, JobApplicationSettingRules, JobApplicantRelationShip;

    protected $fillable = [
        'application_settings',
        'editor_settings',
        'default_template'
    ];

    protected $casts = [
        'job_id' => 'integer'
    ];
}