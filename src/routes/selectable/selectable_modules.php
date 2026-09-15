<?php

use App\Http\Controllers\App\Applicant\EventTypeController;
use App\Http\Controllers\App\Applicant\JobApplicantController;
use App\Http\Controllers\App\Company\CompanyLocationController;
use App\Http\Controllers\App\Company\DepartmentController;
use App\Http\Controllers\App\JobPost\ExperienceController;
use App\Http\Controllers\App\JobPost\JobPostController;
use App\Http\Controllers\App\JobPost\JobTypeController;
use App\Http\Controllers\App\Recruitment\HiringTeamController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {
    Route::get('selectable/departments',[ DepartmentController::class, 'getAllDepartments']);
    Route::get('selectable/departments/search',[ DepartmentController::class, 'getAllDepartmentsBySearch']);
    Route::get('selectable/company-locations',[ CompanyLocationController::class, 'getAllLocations']);
    Route::get('selectable/company-locations/search',[ CompanyLocationController::class, 'getAllLocationsBySearch']);
    Route::get('selectable/event-types', [EventTypeController::class, 'getEventTypes']);
    
    Route::get('selectable/stages', [JobPostController::class, 'selectableStages']);
    Route::get('selectable/job-posts', [JobPostController::class, 'selectableOpenJobPost']);
    Route::get('selectable/job-types', [JobTypeController::class, 'getAllJobTypes']);
    Route::get('selectable/job-experience-levels', [ExperienceController::class, 'getExperiences']);
    Route::get('selectable/{job_post}/hiring-team', [HiringTeamController::class, 'selectableApplicantAttendees']);
    Route::get('job-applicant/selectable/status', [JobApplicantController::class, 'selectableStatus']);

});