<?php

namespace App\Notifications\App\Applicant;

use App\Mail\Tag\EventCreatedTag;
use App\Models\App\Applicant\Applicant;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCreatedNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $event)
    {
        $temp = $event->jobApplicant();
        $this->appliedBy = Applicant::query()->find($temp->first()->applicant_id);
        $this->attendees = $event->attendees()->get();
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $event;
        $this->auth = auth()->user();
        $this->tag = EventCreatedTag::class;

        parent::__construct();
    }


    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('candidates', $this->model->id);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{event_type}' => $this->model->eventType->name,
        ]);

        $returnableArray = [
            '{event_type}' => $this->model->eventType->name,
            '{candidate_name}' => $this->appliedBy->full_name,
            '{event_time}' => $this->model->start_at . ' - ' . $this->model->end_at,
        ];

        if (intval(request()->video_meeting) === 1) {
            $returnableArray = array_merge($returnableArray, [
                '{zoom_start_url}' => $this->model->meeting->start_url,
            ]);
        } else {
            $returnableArray = array_merge($returnableArray, [
                '{zoom_start_url}' => __t('not_scheduled'),
            ]);
        }
        $this->databaseNotificationContent = optional($this->template()->database())->parse($returnableArray);
    }
}