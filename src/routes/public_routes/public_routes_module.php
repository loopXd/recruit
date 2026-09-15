<?php

use App\Http\Controllers\App\JobPost\AggregateJobController;
use App\Http\Controllers\App\JobPost\JobPostController;
use App\Http\Controllers\Candidate\CandidateController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'public'], function () {
    Route::get('job-post/{job_slug}/display', [AggregateJobController::class, 'redirectUrl']);
});


Route::get('career', [CandidateController::class, 'showCareerPage'])->name('candidate.show_career_page');

//career page api
Route::get('job-post-list', [JobPostController::class, 'jobList']);

Route::post('candidate/check-email', [CandidateController::class, 'checkEmail'])->name('candidate.check_email');
Route::get('job-applicant/{job_applicant_slug}/show-job-application', [CandidateController::class, 'showJobApplicantDetails'])
    ->name('candidate.show_job_application');
Route::get('{job_slug}', [AggregateJobController::class, 'showJobPost'])->name('public.jobPost.show_pob_post');
Route::get('{job_slug}/apply', [AggregateJobController::class, 'applyJobPost'])->name('public.jobPost.apply_job_post');
Route::post('{job_slug}/apply', [CandidateController::class, 'applyJobPost'])->name('public.jobPost.apply_job_post.submit');

