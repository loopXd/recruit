<?php

namespace App\Http\Controllers\App\GlobalModules;

use App\Helpers\Core\Traits\FileHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\JobPost\JobPostCoverImageRequest;
use App\Models\App\JobPost\JobPost;
use App\Models\Core\Setting\Setting;
use App\Repositories\Core\Setting\SettingRepository;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Core\Setting\SettingService;
use Illuminate\Http\Request;

class CareerPageController extends Controller
{
    use FileHandler;

    private $setting = null;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $setting = Setting::where('name', 'career_page')->first();
        return array($setting->name => json_decode($setting->value));
    }

    public function showCareerPage(JobPost $jobPost)
    {
        $setting = Setting::where('name', 'career_page')->first();
        $careerPage = json_decode($setting->value);
        $jobPosts = $jobPost
            ->select('id', 'name', 'job_type_id', 'slug', 'company_location_id', 'last_submission_date', 'working_arrangement', 'experience_level_id', 'vacancy_count')
            ->with([
                'experienceLevel:id,name',
                'location:id,address',
                'jobType:id,name',
            ])
            ->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'))
            ->latest('last_submission_date')
            ->take(6)
            ->get();

        return view('career-page.index', ['careerPage' => $careerPage, 'jobPosts' => $jobPosts]);
    }

    private function __setCareerSettingsData()
    {
        $this->setting = Setting::where('name', 'career_page')->first();
    }

    public function update(Request $request)
    {
        $request->validate([
            'career_page' => 'required|array'
        ]);

        $this->__setCareerSettingsData();

        $this->setting->value = json_encode($request->career_page);
        $this->setting->save();
        return updated_responses('career_page');
    }

    public function coverImageUpdate(JobPostCoverImageRequest $request)
    {
        if ($request->hasFile('job_post_cover')) {
            $file = $request->job_post_cover;

            $file_path = $this->withOriginalName()->storeFile($file, 'career_page_cover_image');

            $setting = resolve(SettingRepository::class)
                ->createSettingInstance('job_post_cover', 'app');

            $this->deleteImage(optional($setting)->value);

            $setting->fill([
                'name' => 'job_post_cover',
                'value' => $file_path,
                'context' => 'app',
            ]);

            $setting->save();
        }
        return updated_responses('job_post_cover_image');
    }
}
