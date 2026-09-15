<?php


namespace App\Services\App\Integration;


use App\Repositories\Core\Setting\SettingRepository;
use App\Services\App\AppService;

class ImapSettingService extends AppService
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

    public function imapSettingUpdate()
    {
        $data = array_merge(request()->only(
            'imap_encryption', 'imap_password', 'imap_port', 'imap_server', 'imap_user'
        ) , [
            'imap_validate_cert' => true ,
            'imap_protocol' => 'imap',
        ]);
        foreach ($data as $key => $value) {
            $this->meetingSettingUpdate($key, $value, 'imap_config');
        }
        return $this;
    }
}