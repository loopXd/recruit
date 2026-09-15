<?php

namespace App\Models\App\Applicant\Traits\Relationship;

use App\Models\App\Applicant\JobApplicant;
use App\Models\Core\Auth\User;

trait ApplicantRelationship
{
    public function jobApplicants()
    {
        return $this->hasMany(JobApplicant::class, 'applicant_id');
    }

    public function totalApplication()
    {
        return $this->jobApplicants()
            ->selectRaw('applicant_id, count(*) as count')
            ->groupBy('applicant_id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
