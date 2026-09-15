<?php

use App\Http\Controllers\App\Integration\MeetingController;
use App\Http\Controllers\App\Settings\ImapSettingController;
use App\Http\Controllers\App\Settings\SmsSettingController;
use App\Http\Controllers\App\Settings\SettingsApiController;
use App\Http\Controllers\App\Settings\ReCaptchaSettingController;
use App\Http\Controllers\App\Settings\StorageSettingController;
use App\Http\Controllers\App\Settings\TestMailController;
use Illuminate\Support\Facades\Route;

// App settings
Route::get('/app-setting', [SettingsApiController::class, 'settings'])
    ->name('app.settings');

// Application settings value get from config
Route::get('general-settings', [SettingsApiController::class, 'index']);

// SMS setting
Route::post('/sms-settings', [SmsSettingController::class, 'update'])
    ->middleware('can:update_sms_settings')
    ->name('settings.update-sms');

Route::get('get-sms-setting-info', [SmsSettingController::class, 'getData']);

// ReCAPTCHA setting
Route::post('/re-captcha-setting', [ReCaptchaSettingController::class, 'store'])
    ->middleware('can:update_recaptcha_settings')
    ->name('settings.update-recaptcha');

Route::post('app/test-mail/send', [TestMailController::class, 'send'])
    ->name('test-mail.send');

// Job settings
Route::group(['middleware' => ['permission'], 'as' => 'app_permission.'], function () {
    Route::view('/job-setting', 'settings.job-setting')->name('job_setting.view');
    Route::post('admin/app/settings/zoom-settings', [MeetingController::class, 'update'])
        ->name('settings.update-zoom');

    Route::post('admin/app/settings/imap-settings', [ImapSettingController::class, 'update'])
        ->name('settings.update-imap');

    Route::get('admin/app/meeting/zoom-setting', [MeetingController::class, 'showZoomMeetingSettings'])->name('settings.show-zoom');
    Route::get('admin/app/imap/imap-setting', [ImapSettingController::class, 'imapSettings'])->name('settings.show-imap');
});

//Route::group(['middleware' => ['permission'], 'as' => 'app_permission.'], function () {
    Route::view('/integrations', 'settings.integration')->name('integration.view');
//});

Route::get('admin/app/settings/cronjob', [SettingsApiController::class, 'cronjobData']);

//storage configuration
Route::get('storage-configuration', [StorageSettingController::class, 'index']);
Route::post('storage-configuration', [StorageSettingController::class, 'update']);
