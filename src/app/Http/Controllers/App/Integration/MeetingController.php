<?php


namespace App\Http\Controllers\App\Integration;


use App\Http\Controllers\Controller;
use App\Http\Requests\App\Integration\ZoomMeetingSettingsRequest;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\App\Integration\MeetingService;

class MeetingController extends Controller
{

    public function __construct(MeetingService $meetingService)
    {
        $this->service = $meetingService;
    }
    public function update(ZoomMeetingSettingsRequest $request)
    {
        $this->service
            ->zoomMeetingSettingUpdate();

        return updated_responses('zoom_settings');
    }

    public function showZoomMeetingSettings()
    {

        return resolve(SettingRepository::class)->getDeliverySettingLists('zoom_meeting');
    }
}