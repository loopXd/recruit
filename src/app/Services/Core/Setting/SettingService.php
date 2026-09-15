<?php


namespace App\Services\Core\Setting;


use App\Helpers\Core\Traits\FileHandler;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SettingService extends BaseService
{
    use FileHandler;

    public function update()
    {
        $settings = request()->except('allowed_resource');

        return collect(array_keys($settings))->map(function ($key) use ($settings){

            $setting = resolve(SettingRepository::class)
                ->createSettingInstance($key, 'app');

            if (request()->file($key)) {
                $this->deleteImage(optional($setting)->value);
                $settings[$key] = $this->uploadImage(request()->file($key), config('file.'.$key.'.folder'), config('file.'.$key.'.height'));
            }

            $this->setModel($setting);

            if ($locale = request()->get('language')) {
                session()->put('locale', $locale);
            }

            return parent::save([
                'name' => $key,
                'value' => $settings[$key],
                'context' => 'app'
            ]);
        });

    }

    public function getFormattedSettings($context = 'app'): array
    {
        $settings =  resolve(SettingRepository::class)
            ->getFormattedSettings($context);

        $storage_disk = Storage::disk(config('filesystems.default'));
        $data = [];
        foreach ($settings as $key => $setting) {
            $data[$key] = $setting;
            if (in_array($key, ['company_logo', 'company_icon', 'company_banner'])) {
                if (!$setting){
                    $setting = $this->defaultSetting($key);
                }
                $data[$key] = Storage::disk(config('filesystems.default'))->exists($setting)
                    ? Storage::disk(config('filesystems.default'))->url($setting) : asset($setting);
            }
        }
        return array_merge($settings, $data);
    }

    public function defaultSetting($key)
    {
        $defaultSetting = Arr::first(config('settings.app'), function ($value) use ($key) {
            return $value['name'] === $key;
        });

        return $defaultSetting['value'] ?? null;
    }

    public function saveSettings(array $data, $context, $settingable_type = null, $settingable_id = null)
    {
        foreach ($data as $key => $value) {
            $corn_job = resolve(SettingRepository::class)
                ->createSettingInstance($key, $context, $settingable_type, $settingable_id);

            $corn_job->fill([
                'name' => $key,
                'value' => $value,
                'context' => $context,
                'settingable_type' => $settingable_type,
                'settingable_id' => $settingable_id
            ]);

            $corn_job->save();
        }
        return true;
    }

    public function updateCornJobSetting()
    {
        $this->saveSettings(
            request()->except('allowed_resource'),
            config('settings.corn-job-context')
        );

        return $this->getFormattedSettings(config('settings.corn-job-context'));
    }

    public function getCachedMailSettings()
    {
        return cache()->remember('app-delivery-settings', 86400, function () {
            return $this->getMailSettings();
        });
    }

    public function getMailSettings()
    {
        $service = resolve(DeliverySettingService::class);

        $default = $service
            ->getDefaultSettings();

        return $service
            ->getFormattedDeliverySettings([
                optional($default)->value,
                'default_mail_email_name'
            ]);
    }
}
