<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\EventRelationship;
use App\Models\App\Applicant\Traits\Rules\EventRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends AppModel
{
    use HasFactory, EventRules, EventRelationship;

    protected $fillable = [
        'event_type_id', 'job_applicant_id', 'location', 'start_at', 'end_at', 'description'
    ];

    protected $casts = [
        'event_type_id' => 'integer',
        'job_applicant_id' => 'integer',
    ];
}
