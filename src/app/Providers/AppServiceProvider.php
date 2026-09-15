<?php

namespace App\Providers;

use App\Config\SetStorageConfig;
use App\Helpers\App\Traits\SetSettingsConfig;
use App\Mail\App\Traits\SetMailConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Exception;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if (!$this->app->environment('production') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        setlocale(LC_TIME, config('app.locale_php'));
        Carbon::setLocale(config('app.locale'));

        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }

        Blade::if('readonly', function () {
            return config('app.read_only');
        });

        Blade::if('langrtl', function ($session_identifier = 'lang-rtl') {
            return session()->has($session_identifier);
        });

        try {
            SetMailConfig::new(true)
                ->clear()
                ->set();

            SetStorageConfig::new(true)
                ->set();

            SetSettingsConfig::new(true)
                ->set();

        } catch (Exception $exception){

        }

       /* \Swift_DependencyContainer::getInstance()
            ->register('mime.idgenerator.idright')
            ->asValue(config('mail.host'));*/
    }
}
