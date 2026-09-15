<?php

use App\Http\Controllers\App\JobPost\ExperienceController;
use App\Http\Controllers\App\JobPost\JobAlertController;
use App\Http\Controllers\App\JobPost\SocialSharableController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\JobPost\JobPostController;
use App\Http\Controllers\App\JobPost\JobTypeController;
use App\Http\Controllers\App\JobPost\AggregateJobController;

Route::group(['prefix' => 'app', 'as' => 'app.app_permission.', 'middleware' => ['permission']], function (Router $route) {
    $route->get('dashboard/job-summery', [AggregateJobController::class, 'summery'])
        ->name('job_summery.view');
    $route->get('dashboard/{job_post:id}/overview', [AggregateJobController::class, 'overview'])
        ->name('job_overview.view');
    $route->get('dashboard/{jobPostId}/applicants', [AggregateJobController::class, 'applicants'])
        ->name('job_overview.applicants.view');
    $route->get('job-post/{job}/generate-sharable-link', [AggregateJobController::class, 'sharableLink'])
        ->name('job_post.sharable_link');    
    $route->patch('job-post/{job}/update-job-post', [AggregateJobController::class, 'updateJobPost'])
        ->name('job_post.update_job_post_setting');
    $route->patch('job-post/{job}/publish-job-post', [AggregateJobController::class, 'publishJobPost'])
        ->name('job_post.publish');
    $route->patch('job-post/{job}/edit-apply-form-setting', [AggregateJobController::class, 'editApplyForm'])
        ->name('job_post.update_apply_form_setting');
    $route->patch('job-post/{job_id}/change-status', [JobPostController::class, 'changeStatus'])
        ->name('status.job_post.change');

    $route->patch('bulk-assign/jobs', [AggregateJobController::class, 'bulkAssignJobs'])
        ->name('jobs.bulk_assign');

    $route->patch('bulk-retract/jobs', [AggregateJobController::class, 'bulkRetractJobs'])
        ->name('jobs.bulk_retract');

    $route->get('get/job-posts', [JobPostController::class, 'getAllJobs'])
        ->name('job_post.view');
    
    //-----------------Stage Operation-------------------------------------
    Route::patch('job-post/{job_post_id}/add-stages', [JobPostController::class, 'addStages'])
        ->name('to_job_post.add_stage');
    Route::patch('job-post/{job_post_id}/reorder-stages', [JobPostController::class, 'reorderStages'])
        ->name('of_job_post.update_stage');

    //--------------------Custom view files returned--------------------------
    Route::get('job-post/{job_id}/settings', [AggregateJobController::class, 'viewSetting'])
        ->name('setting.job_post.view');
    Route::get('job-post/{job}/edit-job-post', [AggregateJobController::class, 'editJobPost'])
        ->name('view.setting.job_post.allow');
    Route::get('job-post/{job_post:id}/overview', [AggregateJobController::class, 'viewOverview'])
        ->name('view.job_overview.show');

    //------------------Social Sharable----------------------------------------------
    Route::get('sharable_thumbnail/{id}', [SocialSharableController::class, 'show'])
        ->name('sharable_thumbnail.view');
    Route::patch('sharable_thumbnail/{job_post}/update', [SocialSharableController::class, 'update'])
        ->name('sharable_thumbnail.edit');
    //--------------------------------------------------------------------------------------
    Route::patch('set-job-alert', [JobPostController::class, 'setJobAlert'])
        ->name('set_job_alert');

    Route::get('get-job-alert', [JobPostController::class, 'getJobAlert'])
        ->name('get_job_alert');

});

Route::group(['prefix' => 'app', 'as' => 'app_permission.', 'middleware' => ['permission']], function () {
    Route::apiResource('job-type', JobTypeController::class);
    Route::apiResource('experience-level', ExperienceController::class);
    Route::apiResource('job-post', JobPostController::class);
});

