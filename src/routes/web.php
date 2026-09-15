<?php

use App\Http\Controllers\App\Auth\UserRegisterController;
use App\Http\Controllers\App\Settings\SettingsApiController;
use App\Http\Controllers\Candidate\CandidateController;
use App\Http\Controllers\Core\Auth\User\UserPasswordController;
use App\Http\Controllers\Core\LanguageController;
use App\Http\Controllers\InstallDemoDataController;
use App\Http\Controllers\SymlinkController;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Route;

/**
 * This route is only for user dashboard
 * And for some additional route
 */

//============================================================================
Route::any('symlink', [SymlinkController::class, 'run'])
    ->name('storage.symlink');

Route::group(['middleware' => ['app.installed']], function () {


    Route::get('/', [CandidateController::class, 'showCareerPage'])->name('show_career_page');
    Route::get('/get-basic-setting-data', [SettingsApiController::class, 'getBasicSettingData']);

    Route::get('/forget-password', [UserPasswordController::class, 'passwordReset']);

    Route::get('register', [UserRegisterController::class, 'signUpView'])
        ->name('users.register.index');

    Route::post('auth/user/register', [UserRegisterController::class, 'register'])
        ->name('users.register');

// Switch between the included languages
    Route::get('lang/{lang}', [LanguageController::class, 'swap'])->name('language.change');

// available languages
    Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
    Route::post('lang', [LanguageController::class, 'userLevelLanguageSwap'])
        ->name('lang.swap');

    /*
     * All login related route will be go there
     * Only guest user can access this route
     */

    Route::group(['middleware' => ['app.installed','guest'], 'prefix' => 'user'], function () {
        include_route_files(__DIR__ . '/user/');
    });

    Route::group(['middleware' => ['app.installed', 'guest'], 'prefix' => 'admin/users'], function () {
        include_route_files(__DIR__ . '/login/');
    });

    /**
     * This route is only for brand redirection
     * And for some additional route
     */

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'authorize']], function () {
        include __DIR__ . '/additional.php';
    });


    /**
     * Backend Routes
     * Namespaces indicate folder structure
     * All your route in sub file must have a name with not more than 2 index
     * Example: brand.index or dashboard
     * See @var PermissionMiddleware for more information
     */
    Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'as' => 'core.'], function () {

        /*
         * (good if you want to allow more than one group in the core,
         * then limit the core features by different roles or permissions)
         *
         * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
         * These routes can not be hit if the password is expired
         */
        include_route_files(__DIR__ . '/core/');

    });

    Route::group(['middleware' => ['auth', 'authorize'], 'as' => 'support.'], function () {
        include_route_files(__DIR__ . '/support/');
    });

//===============Include public routes for candidates=======================

    //include_route_files(__DIR__ . '/app/');
    Route::group(['middleware' => ['auth', 'authorize']], function () {
        include_route_files(__DIR__ . '/app/');
    });

    Route::group(['middleware' => ['auth']], function () {
        include_route_files(__DIR__ . '/selectable/');
        include_route_files(__DIR__ . '/auth_global/');
    });

    include_route_files(__DIR__ . '/public_routes/');

});

