<?php

namespace App\Http\Controllers\App\JobPost;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\JobPost\JobApplicationSettingRequest;
use App\Models\App\JobPost\JobApplicationSetting;
use App\Services\App\JobPost\JobApplicationSettingService;

class JobApplicationSettingController extends Controller
{
    public function __construct(JobApplicationSettingService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(JobApplicationSettingRequest $request): array
    {
        $this->service
            ->setAttributes($request->only('application_settings', 'editor_settings', 'template'))
            ->save();

        return created_responses('job_application_setting');
    }

    public function show(JobApplicationSetting $jobSetting): object
    {
        return $jobSetting;
    }

    public function update(JobApplicationSettingRequest $request, JobApplicationSetting $jobSetting)
    {
        $this->service
            ->setModel($jobSetting)
            ->save(
                $request->only('name')
            );

        return updated_responses('job_application_setting');
    }

    public function destroy(JobApplicationSetting $jobSetting)
    {
        $jobSetting->delete();

        return deleted_responses('job_application_setting');
    }
}
