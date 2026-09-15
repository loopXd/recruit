<?php


namespace App\Models\App\JobPost;


use App\Models\App\Applicant\Applicant;
use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use App\Models\Core\File\File;

class ApplicationEmail extends AppModel
{
    protected $fillable = [
        'user_id',
        'applicant_id',
        'job_post_id',
        'sender',
        'message_id',
        'reference_id',
        'text_html'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('type', 'job_post_conversation');
    }
}