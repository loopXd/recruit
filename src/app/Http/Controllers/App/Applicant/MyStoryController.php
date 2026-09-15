<?php

namespace App\Http\Controllers\App\Applicant;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\App\JobPost\JobPost;
use App\Http\Controllers\Controller;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Recruitment\JobStage;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Recruitment\HiringTeam;
use App\Filters\App\Applicant\ApplicantFilter;
use App\Helpers\App\AppOnDeleteRelatedModels;
use App\Services\App\Applicant\ApplicantService;
use App\Repositories\Core\Status\StatusRepository;
use App\Http\Requests\App\Applicant\ApplicantRequest;

class MyStoryController extends Controller
{
    public function __construct(JobApplicant $service)
    {
        $this->service = $service;
    }

    public function index()
    {

        return $this->service
            ->with(
                [
                    'appliedBy', 'jobPost', 'currentStage', 'status', 'answers', 'events'
                ]
            )
            ->whereHas('appliedBy', function ($query) {
                $query->where('email', auth()->user()->email);
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }
}
