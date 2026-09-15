<?php


namespace App\Mail\App;


use App\Mail\Tag\JobAlertTag;
use App\Models\Core\Notification\NotificationTemplate;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCandidateJobAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $candidate;
    public $jobPost;
    public $event;

    public function __construct($event, $candidate, $jobPost)
    {
        $this->event = $event;
        $this->candidate = $candidate;
        $this->jobPost = $jobPost;
    }

    public function build()
    {
        $template =  $this->template();

        $tag = new JobAlertTag( $this->candidate, $this->jobPost);

        return $this->view('notification.template', [
            'template' => $template->parse($tag->notification())
        ])->subject($template->parseSubject([$template->subject]));
    }

    public function template(): NotificationTemplate
    {
        return NotificationTemplateHelper::new()
            ->on($this->event)
            ->mail();
    }
}