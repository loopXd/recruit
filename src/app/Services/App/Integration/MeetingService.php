<?php

namespace App\Services\App\Integration;

use App\Repositories\Core\Setting\SettingRepository;
use App\Services\App\AppService;

class MeetingService extends AppService
{
    public function meetingSettingUpdate($key, $value, $context = null, $settingable_type = null, $settingable_id = null)
    {
        $setting = resolve(SettingRepository::class)
            ->createSettingInstance($key, $context, $settingable_type, $settingable_id);

        $setting->fill([
            'name' => $key,
            'value' => encrypt($value),
            'context' => $context,
            'settingable_type' => $settingable_type,
            'settingable_id' => $settingable_id
        ]);

        $setting->save();

        return $this;
    }

    public function zoomMeetingSettingUpdate()
    {
        foreach (request()->only('api_secret', 'api_key') as $key => $value) {
            $this->meetingSettingUpdate($key, $value, 'zoom_meeting');
        }
        return $this;
    }
}