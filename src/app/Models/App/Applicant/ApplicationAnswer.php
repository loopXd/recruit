<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Rules\ApplicationAnswerRules;
use App\Models\App\AppModel;
use App\Models\App\JobPost\Traits\Relationship\JobApplicationSettingRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationAnswer extends AppModel
{
    use HasFactory, ApplicationAnswerRules, JobApplicationSettingRelationship;

    protected $fillable = ['job_applicant_id', 'question'];

    protected $casts = [
        'job_applicant_id' => 'integer'
    ];
}
