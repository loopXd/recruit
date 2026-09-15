<?php


namespace App\Services\App\Integration;


use App\Repositories\Core\Setting\SettingRepository;
use App\Services\App\AppService;

class GoogleJobSearchService extends AppService
{
    public function update($key, $value, $context = null, $settingable_type = null, $settingable_id = null)
    {
        $setting = resolve(SettingRepository::class)
            ->createSettingInstance($key, $context, $settingable_type, $settingable_id);

        $setting->fill([
            'name' => $key,
            'value' => $value,
            'context' => $context,
            'settingable_type' => $settingable_type,
            'settingable_id' => $settingable_id
        ]);

        $setting->save();
        return true;
    }

}