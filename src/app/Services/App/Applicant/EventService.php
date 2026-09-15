<?php

namespace App\Services\App\Applicant;

use App\Exceptions\GeneralException;
use App\Http\Traits\MeetingZoomTrait;
use App\Mail\App\SendApplicantCustomMail;
use App\Models\App\Applicant\Attendee;
use App\Models\App\Applicant\Event;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\App\Recruitment\Meeting;
use App\Models\Core\Setting\NotificationEvent;
use App\Services\App\AppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EventService extends AppService
{
    private $isNotifiable = true;
    private $attendees = null;
    private $template = [];
    private $meeting = [];

    use MeetingZoomTrait;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function getModelObj()
    {
        return $this->model;
    }

    public function notify($event, $notificationClass): self
    {
        if (class_exists($notificationClass) && $this->isNotifiable) {

            $this->getCreateEventTemplateForCandidate()
                ->sendCandidateEmail();

            notify()
                ->on($event)
                ->mergeAudiences($this->generateAudience())
                ->with($this->model)
                ->send($notificationClass);
        }

        return $this;
    }

    public function generateAudience(): array
    {
        $userIds = [];

        foreach ($this->attendees as $attendee) {
            $user_id = HiringTeam::query()->find($attendee->hiring_team_id);
            array_push($userIds, $user_id->recruiter_id);
        }

        sort($userIds);
        return $userIds;
    }

    public function saveEvent($options = [])
    {
        $attributes = count($options) ? $options : request()->all();
        $this->model
            ->fill(array_merge(
                $this->getFillAble($attributes)
            ))
            ->save();

        return $this;
    }

    public function scheduleZoomMeeting()
    {
        if (intval(request()->video_meeting) === 2)
            return $this;

        $this->meeting = $this->createMeeting();

        Meeting::query()->create(
            array_merge(
                $this->meeting->only(['topic', 'duration', 'start_url', 'join_url']),
                [
                    'user_id' => $this->meeting->user_id,
                    'uuid' => $this->meeting->uuid,
                    'host_id' => $this->meeting->host_id,
                    'host_email' => $this->meeting->host_email,

                    'meeting_id' => (string) $this->meeting->id,
                    'event_id' => $this->model->id,
                    'meeting_channel' => 'zoom',
                ]
            )
        );


        return $this;
    }

    public function isNotifiable($value, $comparedValue)
    {
        $this->isNotifiable = $value === $comparedValue;
        return $this;
    }

    public function addAttendees($attendees)
    {
        throw_if(!isset($this->model->id), new GeneralException(__t('action_not_allowed')));

        $event_id = $this->model->id;

        if (count($attendees) < 1) {
            return $this;
        }

        $attendee = [];
        foreach ($attendees as $att) {
            $result = Attendee::query()->create([
                'event_id' => $event_id,
                "hiring_team_id" => $att['hiring_team_id']
            ]);

            array_push($attendee, $result);
        }

        $this->attendees = $attendee;
        return $this;
    }

    public function sendCandidateEmail()
    {
        Mail::to($this->model->jobApplicant->appliedBy->email)
            ->send(new SendApplicantCustomMail($this->template, $this->model));

        return $this;

    }

    public function getCreateEventTemplateForCandidate()
    {
        $notificationEvent = NotificationEvent::with('templates')
            ->where('name', 'create_event_mail_for_candidate')
            ->first();

        $template = $this->getTemplate($notificationEvent);

        $replaceableValues = $this->getReplaceableValues();

        $subject = count($notificationEvent->templates) > 0 ? optional($notificationEvent)->templates[0]->subject : '';

        $this->template = (object) array(
            'mail' => strtr($template, $replaceableValues),
            'subject' => strtr($subject, $replaceableValues)
        );

        return $this;
    }

    public function getReplaceableValues()
    {
        $logo = config()->get('settings.application.company_logo');
        $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->model->start_at);
        $ended_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->model->end_at);

        $replaceableValues = [
            '{description}' => $this->model->description,
            '{location}' => $this->model->location,

            '{start_at}' => $this->model->start_at,
            '{end_at}' => $this->model->end_at,
            '{start_at_query}' => "day=" . $started_at->format('d') . "&month=" . $started_at->format('m') . "&year=" . $started_at->format('Y') . "&hour=" . $started_at->format('H') . "&min=" . $started_at->format('i') . "&sec=" . $started_at->format('s') . "&p1=0",
            '{end_at_query}' => "day=" . $ended_at->format('d') . "&month=" . $ended_at->format('m') . "&year=" . $ended_at->format('Y') . "&hour=" . $ended_at->format('H') . "&min=" . $ended_at->format('i') . "&sec=" . $ended_at->format('s') . "&p1=0",

            '{event_type}' => $this->model->eventType->name,
            '{job_post}' => $this->model->jobApplicant->jobPost->name,
            '{candidate_name}' => $this->model->jobApplicant->appliedBy->name,
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo),
            '{app_name}' => config('app.name'),
        ];

        $isMeetingEnabled = intval(request()->video_meeting) === 1;

        $replaceableValues = array_merge($replaceableValues, [
            '{zoom_join_url}' => $isMeetingEnabled ? $this->model->meeting->join_url : __t('not_scheduled'),
            '{zoom_meeting_id}' => $isMeetingEnabled ? $this->model->meeting->meeting_id : __t('not_scheduled'),
            '{topic}' => $isMeetingEnabled ? $this->model->meeting->topic : __t('not_scheduled'),
            '{duration}' => $isMeetingEnabled ? $this->model->meeting->duration : __t('not_scheduled'),
        ]);

        return $replaceableValues;
    }

    private function getTemplate($notificationEvent)
    {
        if (count(optional($notificationEvent)->templates) > 0) {
            return optional($notificationEvent)->templates[0]->custom_content
                ? optional($notificationEvent)->templates[0]->custom_content
                : optional($notificationEvent)->templates[0]->default_content;
        }
        return '';
    }

    public function updateOrCreateZoomMeeting()
    {
        if (intval(request()->video_meeting) === 2) {
            if ($this->model->meeting) {
                $this->deleteZoomMeeting($this->model->meeting->meeting_id);
                $this->model->meeting->delete();
            }

            return $this;
        }

        if ($this->model->meeting) {

            $meeting = Meeting::query()->find($this->model->meeting->id);
            $meeting->topic = request()->topic;
            $meeting->duration = request()->duration;
            $meeting->save();

            $zoom = $this->newZoomInstance();
            $zoom->meeting()
                ->find(intval($this->model->meeting->meeting_id))
                ->update(array_merge(
                    request()->only(['topic', 'duration']),
                    ['start_time' => request()->start_at]
                ));

        } else {
            $this->scheduleZoomMeeting();
        }

        return $this;
    }
}
