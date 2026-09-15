<?php

namespace App\Http\Controllers\App\JobPost;

use App\Helpers\traits\SiteMapUpdateHelper;
use App\Http\Requests\App\JobPost\JobAlertRequest;
use App\Models\App\JobPost\JobAlert;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\App\JobPost\JobPost;
use App\Http\Controllers\Controller;
use App\Models\App\Recruitment\Stage;
use App\Models\App\Recruitment\JobStage;
use App\Filters\App\JobPost\JobPostFilter;
use App\Helpers\App\AppOnDeleteRelatedModels;
use App\Models\App\Recruitment\HiringTeam;
use App\Services\App\JobPost\JobPostService;
use App\Http\Requests\App\JobPost\JobPostRequest;
use App\Repositories\Core\Status\StatusRepository;

class JobPostController extends Controller
{
    use SiteMapUpdateHelper;

    public function __construct(JobPostService $service, JobPostFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): object
    {
        return $this->service
            ->filters($this->filter)
            ->with([
                'location:id,address',
                'department:id,name',
                'jobType:id,name',
                'experienceLevel:id,name',
                'status:id,name,class,type',
                'jobStages:id,job_post_id,name',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with([
                            'jobApplicantCount',
                            'jobApplicants.appliedBy:id,first_name,last_name,email',
                            'jobApplicants.status:id,name,class,type',
                        ]);
                },
                'totalApplicants',
            ])
            ->when(!request()->get('status'), function ($query) {
                $query->where('status_id', '!=', resolve(StatusRepository::class)->getStatusId('job_post', 'status_closed'));
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }


    public function jobList()
    {
        return $this->service
            ->filters($this->filter)
            ->select('id', 'name', 'job_type_id', 'slug', 'company_location_id', 'last_submission_date', 'salary', 'department_id', 'is_viewable', 'working_arrangement', 'experience_level_id', 'vacancy_count')
            ->with([
                'experienceLevel:id,name',
                'location:id,address',
                'jobType:id,name',
                'department:id,name',
            ])
            ->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'))
            ->whereDate('last_submission_date', '>=', now()->format('Y-m-d'))
            ->latest('id')
            ->paginate(request()->get('per_page', 10));
    }

    public function store(JobPostRequest $request): array
    {
        $inputs = $request->only([
            'company_location_id',
            'department_id',
            'job_type_id',
            'name',
            'stages',
            'salary',
            'vacancy_count',
            'is_viewable',
            'experience_level_id',
            'working_arrangement',
            'description',
            'responsibilities',
            'job_post_settings',
            'apply_form_settings',
            'last_submission_date',
        ]);
        $newData = ['posted_by' => auth()->id(), 'status_id' => resolve(StatusRepository::class)->getStatusId('job_post', 'status_draft')];
        $inputs = array_merge($inputs, $newData);
        $inputs["salary"] = str_replace(',', '.', str_replace('.', '', $inputs["salary"]));
        $inputs["slug"] = Str::uuid();

        $result = $this->service
            ->setAttributes($inputs)
            ->save();

        if ($result) {
            $jobStage = $this->getJobStages($request->stages, $result->id);
            JobStage::query()->insert($jobStage);
            HiringTeam::create([
                'job_post_id' => $result->id,
                'recruiter_id' => auth()->id(),
            ]);

            $this->notifyGoogleIndex($result, 'update');
            $this->updateSiteMap();
            return created_responses('job_post');
        }

        return custom_failed_response('failed_to_create_job');
    }

    public function show(JobPost $job, $id)
    {
        return $job->with([
            'location:id,address',
            'department:id,name',
            'jobType:id,name',
            'experienceLevel:id,name',
            'jobPostThumbnail',
            'status:id,name,class,type',
            'jobStages' => function ($query) {
                $query->select('id', 'name', 'job_post_id')
                    ->with([
                        'jobApplicantCount',
                    ]);
            },
        ])->find($id);
    }

    public function update(JobPostRequest $request, JobPost $jobPost)
    {
        $inputs = $request->only([
            'company_location_id',
            'department_id',
            'job_type_id',
            'name',
            'salary',
            'vacancy_count',
            'experience_level_id',
            'working_arrangement',
            'description',
            'responsibilities',
            'job_post_settings',
            'apply_form_settings',
            'last_submission_date',
            'is_viewable',
        ]);
        $inputs["salary"] = str_replace(',', '.', str_replace('.', '', $inputs["salary"]));

        $this->service
            ->setModel($jobPost)
            ->save($inputs);

        $this->notifyGoogleIndex($jobPost, 'update');
        $this->updateSiteMap();

        return updated_responses('job_post');
    }

    public function destroy(JobPost $jobPost, AppOnDeleteRelatedModels $model)
    {
        $model = $model->setModel($jobPost);
        $data = $model->loadRelatedModelsOnDeleteJobPost();
        $model->removeData();
        $jobPost->delete();

        $this->updateSiteMap();
        return deleted_responses('job_post');
    }

    public function getStages(JobPost $jobPost, $job_post_id)
    {
        return $jobPost
            ->select('id', 'name', 'slug', 'created_at', 'stages')
            ->with([
                'jobStages:id,job_post_id,name',
            ])->find($job_post_id);
    }

    private function getJobStages($stages, $jobId)
    {
        $stages = explode(',', $stages);
        $stageArray = [];
        foreach ($stages as $stage) {
            $stageArray[] = ['job_post_id' => $jobId, 'name' => $stage];
        }

        return $stageArray;
    }

    public function addStages(Request $request, JobPost $jobPost, JobStage $jobStage, $jobId)
    {
        $request->validate([
            'new_stages' => 'nullable|string',
            'final_sorted_stages' => 'required|string',
        ]);
        $jobPost = $jobPost->find($jobId);
        $jobPost->stages = $request->final_sorted_stages;
        $jobPost->save();

        if (isset($request->new_stages) && $request->new_stages != null) {
            $new = [];
            $stages = explode(',', $request->new_stages);
            foreach ($stages as $stage) {
                $new[] = ['job_post_id' => intval($jobId), 'name' => $stage];
            }
            $jobStage->insert($new);

            return created_responses('job_stage');
        }
    }

    public function reorderStages(Request $request, JobPost $jobPost, $jobId)
    {
        $request->validate([
            'final_sorted_stages' => 'required|string',
        ]);
        $jobPost = $jobPost->find($jobId);
        $jobPost->stage = $request->final_sorted_stages;
        $jobPost->save();

        return created_responses('job_stage_reordered');
    }

    //--------------------Aggregate Methods------------------------------------------------------

    public function changeStatus(JobPost $jobPost, Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required',
        ]);

        $jobPost = $jobPost->find($id);
        $jobPost->status_id = intval($request->status_id);
        $jobPost->save();

        $this->notifyGoogleIndex($jobPost, $jobPost->status->name === 'status_closed' ? 'delete' : 'update');
        $this->updateSiteMap();

        return status_response('job_post', strtolower(__t($jobPost->load('status')->status->name)));
    }

    public function selectableStages()
    {
        return Stage::query()->get(['id', 'name']);
    }

    public function selectableOpenJobPost()
    {
        $status_open_id = resolve(StatusRepository::class)->getStatusId('job_post', 'status_open');

        return JobPost::query()->where('status_id', $status_open_id)->get(['id', 'name']);
    }

    public function getAllJobs(Request $request)
    {
        return $this->service->select('id', 'name')->get();
    }

    public function setJobAlert(JobAlertRequest $request)
    {
        $existingJobAlert = JobAlert::query()->where('user_id', auth()->user()->id)->first();
        $jobAlertData = array_merge($request->only('department', 'experience_level', 'job_type', 'working_arrangement', 'is_active'), [
            'user_id' => auth()->user()->id
        ]);

        if ($existingJobAlert) {
            $existingJobAlert->update($jobAlertData);
        } else {
            JobAlert::query()->create($jobAlertData);
        }
        return created_responses('job_alert');
    }

    public function getJobAlert(Request $request)
    {
        return JobAlert::query()->where('user_id', auth()->user()->id)->first();
    }
}
