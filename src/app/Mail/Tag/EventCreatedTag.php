<?php

namespace App\Mail\Tag;

use App\Models\App\Applicant\Applicant;
use App\Models\App\JobPost\JobPost;
use Carbon\Carbon;

class EventCreatedTag extends Tag
{
    public function __construct($event, $notifier = null, $receiver = null)
    {
        $temp = $event->jobApplicant();
        $this->appliedBy = Applicant::query()->find($temp->first()->applicant_id);
        $this->jobPost = JobPost::query()->find($temp->first()->job_post_id);
        $this->event = $event;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->auth = auth()->user();
        $this->resourceurl = '';
    }

    function notification()
    {
        $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->event->start_at);
        $ended_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->event->end_at);
        $returnableArray = array_merge([
            '{receiver_name}' => $this->receiver->full_name,
            '{action_by}' => $this->auth,
            '{event_type}' => $this->event->eventType->name,
            '{candidate_name}' => $this->appliedBy->full_name,
            '{start_at}' => $this->event->start_at,
            '{end_at}' => $this->event->end_at,

            '{start_at_query}' => "day=" . $started_at->format('d') . "&month=" . $started_at->format('m') . "&year=" . $started_at->format('Y') . "&hour=" . $started_at->format('H') . "&min=" . $started_at->format('i') . "&sec=" . $started_at->format('s') . "&p1=0",
            '{end_at_query}' => "day=" . $ended_at->format('d') . "&month=" . $ended_at->format('m') . "&year=" . $ended_at->format('Y') . "&hour=" . $ended_at->format('H') . "&min=" . $ended_at->format('i') . "&sec=" . $ended_at->format('s') . "&p1=0",

            '{job_post}' => $this->jobPost->name,
            '{event_location}' => $this->event->location,
            '{app_name}' => config('settings.application.company_name'),

        ], $this->common());


        if (intval(request()->video_meeting) === 1){
            $returnableArray = array_merge($returnableArray, [
                '{zoom_start_url}' =>  $this->event->meeting->start_url,
                '{zoom_join_url}' =>  $this->event->meeting->join_url,
                '{zoom_meeting_id}' =>  $this->event->meeting->meeting_id,
                '{topic}' =>  $this->event->meeting->topic,
                '{duration}' =>  $this->event->meeting->duration,
            ]);
        }else{
            $returnableArray = array_merge($returnableArray, [
                '{zoom_start_url}' =>   __t('not_scheduled'),
                '{zoom_meeting_id}' =>   __t('not_scheduled'),
                '{topic}' =>   __t('not_scheduled'),
                '{duration}' =>   __t('not_scheduled'),
            ]);
        }

        return $returnableArray;
    }
}