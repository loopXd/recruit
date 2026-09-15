<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\NoteRelationship;
use App\Models\App\Applicant\Traits\Rules\NoteRules;
use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends AppModel
{
    use HasFactory, NoteRules, NoteRelationship;

    protected $fillable = ['job_applicant_id', 'noted_by', 'description'];

    protected $casts = [
        'job_applicant_id' => "integer",
        'noted_by' => "integer"
    ];
}
