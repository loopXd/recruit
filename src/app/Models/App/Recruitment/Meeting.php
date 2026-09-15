<?php


namespace App\Models\App\Recruitment;


use App\Models\App\AppModel;
use App\Models\App\Recruitment\Traits\Relationship\MeetingRelationship;
use App\Models\App\Recruitment\Traits\Rules\MeetingRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends AppModel
{
    use HasFactory, MeetingRules, MeetingRelationship;

    protected $fillable = ['event_id', 'meeting_id', 'topic', 'duration', 'password',
        'start_url', 'join_url', 'meeting_channel',
        'user_id',
        'uuid',
        'host_id',
        'host_email',
    ];

    protected $casts = [
        'event_id' => 'int',
        'meeting_id' => 'int',
    ];
}

{

}