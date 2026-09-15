<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\AttendeeRelationship;
use App\Models\App\Applicant\Traits\Rules\AttendeeRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendee extends AppModel
{
    use HasFactory, AttendeeRules, AttendeeRelationship;

    protected $fillable = ['event_id', 'hiring_team_id'];

    protected $casts = [
        'event_id' => 'int',
        'hiring_team_id' => 'int'
    ];

    public $timestamps = false;
}
