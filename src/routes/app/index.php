<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'admin'], function () {

    include_route_files(__DIR__.'/feature/');
});