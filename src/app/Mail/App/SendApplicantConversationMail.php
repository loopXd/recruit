<?php


namespace App\Mail\App;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\App\JobPost\ApplicationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendApplicantConversationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels,FileHandler;

    public $template;
    public $subject;
    public $event_data = [];
    public $paths;

    public function __construct($model, $data= [])
    {
        $this->template = $data['text_html'] ?? null;
        $this->subject = $data['subject'];
        $this->paths = $data['paths'] ?? [];

        $lastMail = ApplicationEmail::query()
            ->where('applicant_id', $model->applicant_id)
            ->where('job_post_id', $model->job_post_id)
            ->latest('id')->first();

        $this->event_data = [
            'job_post_id' => $model->job_post_id,
            'applicant_id' => $model->applicant_id,
            'user_id' => $data['user_id'],
            'reference_id' => $lastMail->message_id ?? null,
            'attachments' => $this->paths,
        ];
    }

    public function build()
    {
        $message = $this->view('notification.template', [
            'template' => $this->template,
            'event_data' => $this->event_data ?? [],
        ])->subject($this->subject);

        foreach ($this->paths as $path) {
            $message->attach(Storage::disk(config('filesystems.default'))->exists($path)
                ? Storage::disk(config('filesystems.default'))->url($path) : asset($path));
        }
        return $message;
    }
}