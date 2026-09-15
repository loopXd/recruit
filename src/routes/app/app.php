<?php

use App\Http\Controllers\App\NavigationController;
use App\Http\Controllers\App\Roles\RoleController;
use App\Http\Controllers\App\Users\AppUserRoleController;
use App\Http\Controllers\App\Users\UserController;
use App\Http\Controllers\App\Users\AppUserController;
use App\Http\Controllers\App\Users\UserRoleController;
use App\Http\Controllers\App\Users\UserSocialLinkController;
use App\Http\Controllers\App\Auth\AuthenticateUserController;
use App\Http\Controllers\App\Notification\NotificationController;
use App\Http\Controllers\App\Settings\ReCaptchaSettingController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/user/registration', [AuthenticateUserController::class, 'registerView']);
Route::get('/reset/password', [AuthenticateUserController::class, 'resetPasswordView']);
Route::view('/my-profile', 'user.profile');

// Update user name
Route::group(['as' => 'app.', 'middleware' => ['permission']], function (Router $route) {
    Route::post('/update-user-name/{id}', [UserController::class, 'updateUserName'])->name('name.update_user');
    Route::resource('user-list', UserController::class);
});

// Role
Route::get('users/{role}', [RoleController::class, 'getUsersByRole']);
Route::get('all-roles', [RoleController::class, 'getAllRoles']);

// User
Route::get('all-users', [UserController::class, 'getUsers']);
Route::get('get/users', [AppUserController::class, 'index']);
Route::delete('user-delete/{user}', [AppUserRoleController::class, 'destroy'])
    ->name('user_delete');

Route::delete('cancel-invitation/{user}', [AppUserRoleController::class, 'cancelInvitation'])
    ->name('cancel_invitation');

// Role_user
Route::post('attach-user/{role}', [UserRoleController::class, 'store']);

// Sample Pages Routes
Route::view('/blank-page', 'sample-pages.sample');


// ALl Notifications page
Route::get('/all-notifications', [NotificationController::class, 'view']);

Route::get('login-user', [AuthenticateUserController::class, 'show'])
    ->name('user.login-user');

Route::post('change-social-link', [UserSocialLinkController::class, 'update'])
    ->name('user.change-social-link');

// Get captcha
Route::get('/get-re-captcha-setting', [ReCaptchaSettingController::class, 'getReCaptchaSettings'])
    ->name('re-captcha-settings.get');

Route::group(['prefix' => 'admin'], function () {
    Route::view('/candidates', 'candidates.index')->name('candidates');
    Route::view('/my-story', 'candidates.my_story')->name('my-story.list');
    Route::get('/users-and-roles', function () {
        if(authorize_any(['view_roles'])){
            return view('user-roles.user-roles');
        }
        $home_route = home_route();
        return redirect()->route($home_route['route_name']);
    })->name('user-role.list');
});


// Blade files

Route::view('/all-events', 'all-events.index')->name('all_events');
