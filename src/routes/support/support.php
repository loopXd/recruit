<?php

use App\Http\Controllers\App\Settings\JobPointDeliveryController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => ''], function (Router $router) {
    $router->get('check-mail-settings', [JobPointDeliveryController::class, 'isExists'])
        ->name('check-mail-settings');
});