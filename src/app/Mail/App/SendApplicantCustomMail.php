<?php


namespace App\Mail\App;


use App\Models\App\JobPost\ApplicationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendApplicantCustomMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $template;
    public $subject;
    public $event_data = ['name' => "system mail"];
    public $model;

    public function __construct($data, $model)
    {
        $this->template = isset($data->mail) ? $data->mail : null;
        $this->subject = $data->subject;
        $this->model = $model;

        if(get_class($model) === 'App\Models\App\Applicant\JobApplicant'){
            $this->event_data = [
                'job_post_id' => $this->model->job_post_id,
                'applicant_id' => $this->model->applicant_id,
                'reference_id' => null,
                'attachments' => $data->attachments ?? [],
            ];

        }else{
            $this->event_data = [
                'job_post_id' => $this->model->jobApplicant->jobPost->id,
                'applicant_id' => $this->model->jobApplicant->applicant_id,
                'reference_id' => null,
                'attachments' => $data->attachments ?? [],
            ];
        }
    }

    public function build()
    {
        return $this->view('notification.template', [
            'template' => $this->template,
            'event_data' => $this->event_data ?? [],
        ])->subject($this->subject);
    }
}