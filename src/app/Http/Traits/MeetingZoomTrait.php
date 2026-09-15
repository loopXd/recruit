<?php


namespace App\Http\Traits;


use App\Exceptions\GeneralException;
use App\Repositories\Core\Setting\SettingRepository;
use MacsiDigital\Zoom\Facades\Zoom;

trait MeetingZoomTrait
{
    public function createMeeting()
    {
        $zoom = $this->newZoomInstance();
        $user = $zoom->user()->first();
        $meeting = $zoom->meeting()->make($this->meetingData());
        $meeting->settings()->make($this->meetingSettings());

        return $user->meetings()->save($meeting);
    }

    public function newZoomInstance()
    {
        $appZoomSettings = $this->showZoomMeetingSettings();

        throw_if(!$appZoomSettings, new GeneralException(trans('default.invalid_zoom_set_up')));

        return new \MacsiDigital\Zoom\Support\Entry(
            $appZoomSettings['api_key'] ? $appZoomSettings['api_key'] : config('zoom.api_key'),
            $appZoomSettings['api_secret'] ? $appZoomSettings['api_secret'] : config('zoom.api_secret'),
            config('zoom.token_life'),
            config('zoom.max_api_calls_per_request'),
            config('zoom.base_url')
        );
    }

    public function meetingData()
    {
        return [
            'topic' => request('topic'),
            'duration' => request('duration'),
            'password' => request('password'),
            'start_time' => request('start_time'),
            //'timezone' => config('zoom.timezone')
            'timezone' => 'UTC'
        ];
    }

    public function meetingSettings()
    {
        return [
            'join_before_host' => config('zoom.join_before_host'),
            'host_video' => config('zoom.host_video'),
            'participant_video' => config('zoom.participant_video'),
            'mute_upon_entry' => config('zoom.mute_upon_entry'),
            'waiting_room' => config('zoom.waiting_room'),
            'approval_type' => config('zoom.approval_type'),
            'audio' => config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording')
        ];
    }

    public function showZoomMeetingSettings()
    {
        return resolve(SettingRepository::class)->getDeliverySettingLists('zoom_meeting');
    }

    public function deleteZoomMeeting($meetingId)
    {
        $zoom = $this->newZoomInstance();

        $zoom = $zoom->meeting()->find($meetingId);

        if($zoom){
            $zoom->delete();
        }

        return $this;

    }
}