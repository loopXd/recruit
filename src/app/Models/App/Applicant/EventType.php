<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\EventTypeRelationship;
use App\Models\App\Applicant\Traits\Rules\EventTypeRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends AppModel
{
    use HasFactory, EventTypeRules, EventTypeRelationship;

    protected $fillable = ['name'];
}
