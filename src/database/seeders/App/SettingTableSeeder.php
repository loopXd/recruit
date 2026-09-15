<?php

namespace Database\Seeders\App;

use App\Models\Core\Setting\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->where('context', 'app')->delete();
        Setting::query()->insert(
            config('settings.app')
        );
        $storageSetting =  Setting::query()->where('context', 'storage_configuration')->first();
        if (!$storageSetting) {
            Setting::query()->insert(
                [
                    'name' => 'storage_type',
                    'value' => encrypt('public'),
                    'settingable_type' => null,
                    'settingable_id' => null,
                    'context' => 'storage_configuration',
                    'autoload' => 0,
                    'public' => 1,
                ]
            );
        }

    }
}
