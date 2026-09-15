<?php

namespace App\Jobs;

use App\Mail\App\SendCandidateJobAlertMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class JobAlertEmailSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $candidate;
    protected $jobPost;

    public function __construct($candidate, $jobPost)
    {
        $this->candidate = $candidate;
        $this->jobPost = $jobPost;
    }

    public function handle()
    {
        Mail::to($this->candidate->email)->send(new SendCandidateJobAlertMail('job_alert', $this->candidate, $this->jobPost));
    }
}
