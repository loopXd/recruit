<?php

use App\Http\Controllers\App\GlobalModules\CareerPageController;
use App\Http\Controllers\App\GlobalModules\ApplicationFormController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app/global', 'as'=>'app.app_permission.global.', 'middleware' => ['permission']], function () {
    //Custom Aggregate Route Goes Here
    
    Route::get("application-form", [ApplicationFormController::class,'show'])->name('show-application-form');
    Route::patch("application-form", [ApplicationFormController::class,'update'])->name('update-application-form');
    
});
Route::group(['prefix' => 'app/', 'middleware' => ['permission'], 'as'=>'app.app_permission.'], function () {
    //Custom Aggregate Route Goes Here
    Route::patch("career-page", [CareerPageController::class,'update'])->name('career-page.update');
    Route::post("cover-image", [CareerPageController::class,'coverImageUpdate'])->name('cover-image.update');
});

Route::group(['prefix' => 'admin', 'middleware' => ['permission'], 'as' => 'app_permission.'], function () {
    Route::get("career-page", [CareerPageController::class,'showCareerPage'])->name('career-page.show');
});