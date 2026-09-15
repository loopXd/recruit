<?php

use App\Http\Controllers\App\Applicant\JobApplicantController;
use App\Http\Controllers\App\JobPost\JobPostController;
use App\Http\Controllers\App\Recruitment\TodoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    // Custom Aggregate Route Goes Here
    Route::get('job-applicant/{job_applicant}/disqualify-template', [JobApplicantController::class, 'getDisqualifyTemplate']);
    Route::get('job-post/{job_post_id}/get-stages', [JobPostController::class, 'getStages']);

    // Todo
    Route::put('todo/clear-all', [TodoController::class, 'clearAll'])
        ->name('todo.clear_all');
    Route::patch('todo/{todo_id}/change-status', [TodoController::class, 'changeStatus'])
        ->name('todo.change_status');

    Route::apiResource('todo', TodoController::class)->except(['update', 'show']);
});
