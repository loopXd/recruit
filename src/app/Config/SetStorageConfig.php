<?php

namespace App\Config;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Services\Core\Setting\DeliverySettingService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SetStorageConfig
{
    use InstanceCreator;
    protected mixed $settings;

    public function __construct($settings = [])
    {
        $this->settings = $settings;
    }

    public function clear()
    {
        Artisan::call('optimize:clear');
        return $this;
    }

    public function set()
    {
        // first check setting exists or not.
        try {
            $settings = $this->getSettings();
            if (!empty($settings['filesystem_disk'])) {
                // set email and name
                $this->setDefaultFilesystemDisk($settings);
                // set individual mail config
                $method = 'set' . Str::studly($settings['filesystem_disk']);
                if (method_exists($this, $method)) {
                    $this->{$method}($settings);
                }
                return true;
            }
        } catch (\Exception $e) {

        }

        // if not then set from env
        $settings = $this->getSettingsFromEnv();
        $this->setDefaultFilesystemDisk($settings);
        return true;
    }

    /**
     * @return array
     */
    public function getSettingsFromEnv()
    {
        return [
            'filesystem_disk' => env('FILESYSTEM_DISK', 'public'),
            'aws_access_key' => env('AWS_ACCESS_KEY_ID'),
            'aws_secret_key' => env('AWS_SECRET_ACCESS_KEY'),
            'aws_region' => env('AWS_DEFAULT_REGION'),
            'aws_bucket_name' => env('AWS_BUCKET'),
            's3_aws_use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
        ];
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        if (!count($this->settings)) {
            $storage_config = resolve(DeliverySettingService::class)->getCachedFormattedStorageSettings('storage_configuration');

            if ($storage_config) {
                $storage_config['filesystem_disk'] = $storage_config['storage_type'] ?? 'public';
            }

            $this->settings = $storage_config;
        }
        return $this->settings;
    }

    public function setDefaultFilesystemDisk(array $settings): void
    {
        config()->set('filesystems.default', $settings['filesystem_disk']);
    }

    public function setPublic(array $settings): void
    {
        config()->set('filesystems.default', 'public');
    }

    public function setS3(array $settings): void
    {
        config()->set('filesystems.default', 's3');
        config()->set('filesystems.disks.s3.key', $settings['aws_access_key']);
        config()->set('filesystems.disks.s3.secret', $settings['aws_secret_key']);
        config()->set('filesystems.disks.s3.region', $settings['aws_region']);
        config()->set('filesystems.disks.s3.bucket', $settings['aws_bucket_name']);
        config()->set('filesystems.disks.s3.use_path_style_endpoint', $settings['s3_aws_use_path_style_endpoint'] ?? false);
    }
}
