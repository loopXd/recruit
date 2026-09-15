<?php

namespace App\Http\Controllers\Candidate;

use App\Exceptions\GeneralException;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\User;
use App\Services\App\Applicant\CandidateService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\App\JobPost\JobPost;
use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Models\App\Applicant\Applicant;
use App\Helpers\Core\Traits\FileHandler;
use App\Models\App\Recruitment\JobStage;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\Applicant\JobApplicantService;
use App\Notifications\App\Applicant\AppliedJobNotification;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    use FileHandler;

    public function __construct(CandidateService $candidateService)
    {
        $this->service = $candidateService;
    }

    public function showJobApplicantDetails(JobApplicant $applicant, $slug)
    {
        return $applicant
            ->select(
                'id',
                'applicant_id',
                'job_post_id',
                'current_stage_id',
                'status_id',
                'slug',
                'review',
                'created_at'
            )
            ->with([
                'status:id,name,type',
                'appliedBy:id,first_name,last_name,email',
                'jobPost:id,name,slug',
                'currentStage:id,name',
                'answers:id,question,answer,attachment',
            ])
            ->where('slug', $slug)->first();
    }

    public function showCareerPage(JobPost $jobPost)
    {
        $setting = Setting::where('name', 'career_page')->first();
        $careerPage = json_decode($setting->value);

        return view('candidates.career-page', ['careerPage' => $careerPage]);
    }

    public function checkEmail(Request $request)
    {

        $request->validate([
            'job_post_id' => 'required | exists:job_posts,id',
            'email_address' => 'required|email',
        ]);

        $applicant = Applicant::where('email', $request->email_address)->first();

        if (!$applicant) {
            return;
        }
        $jobApplicant = JobApplicant::where('job_post_id', $request->job_post_id)
            ->where('applicant_id', $applicant->id)
            ->first();

        if ($jobApplicant) {
            return response()->json([
                'message' => __t('already_applied_with_this_email'),
                'errors' => [
                    'email' => [
                        __t('already_applied_with_this_email')
                    ]
                ],
            ], 500);
        }

        return $applicant;
    }

    public function applyJobPost(Request $request, $slug)
    {
        if (empty(auth()->id())) {
            return redirect()->route('users.login');
        }

        $applicant = Applicant::find($request->applicant_id);
        $applicant_id = $applicant ? $applicant->id : $this->service
            ->validateApplicant()
            ->storeApplicant()
            ->id;

        $this->candidateProfileCreateOrUpdate($request);

        $jobPost = JobPost::query()->where('slug', $slug)->first();

        if (!$jobPost) {
            return custom_failed_response('invalid_job_post');
        }

        $jobApplicant = $this->assignJobToApplicant(
            $applicant_id,
            $jobPost->id,
            $request->apply_form_setting
        );

        if (!$jobApplicant) {
            Applicant::destroy($applicant_id);

            return custom_failed_response('already_applied_with_this_email');
        }


        $answers = $this->storeQuestionAnswers($jobApplicant->id);

        if (!$answers) {
            return custom_failed_response('failed_to_store_job_applicant');
        }

        if ($jobApplicant) {
            resolve(JobApplicantService::class)
                ->setModel($jobApplicant)
                ->setNotifyCandidateForJobApply(true)
                ->notify('job_applied', AppliedJobNotification::class);

            //Store to timeline
            $description = trans('default.timeline_for_applied_job');
            $find = ['{candidate_name}', '{job_post_name}'];
            $replace = [$jobApplicant->appliedBy->full_name, $jobPost->name];
            $description = str_replace($find, $replace, $description);
            log_to_database($description, [], 'timeline', null, $jobApplicant);
        }

        return custom_response('candidate_applied_successfully');
    }

    private function candidateProfileCreateOrUpdate(Request $request): void
    {
        $basicInformation = json_decode($request->basic_information, true);
        $applyFormSetting = json_decode($request->apply_form_setting, true);

        $gender = match ($basicInformation['gender'] ?? null) {
            'Masculino' => 'male',
            'Feminino' => 'female',
            default => 'other'
        };

        Profile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'apply_form_setting' => json_encode([
                    $applyFormSetting[0] ?? null,
                    $applyFormSetting[1] ?? null,
                    $applyFormSetting[2] ?? null
                ]),
                'gender' => $gender,
                'date_of_birth' => $basicInformation['date_of_birth'] ?? null,
                'contact' => $basicInformation['contact'] ?? null,
                'phone' => $basicInformation['phone'] ?? null
            ]
        );
    }

    private function assignJobToApplicant($applicantId, $jobPostId, $applyFormSetting)
    {
        $jobApplicant = JobApplicant::where('job_post_id', $jobPostId)
            ->where('applicant_id', $applicantId)->first();

        if ($jobApplicant) {
            return false;
        }

        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $jobPostId)->first();

        return JobApplicant::query()->create([
            'applicant_id' => $applicantId,
            'job_post_id' => $jobPostId,
            'current_stage_id' => $stage->id ?? null,
            'apply_form_setting' => $applyFormSetting,
            'status_id' => resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
            'slug' => Str::uuid(),
        ]);
    }

    private function storeQuestionAnswers($jobApplicantId): array
    {
        $fileRecords = [];

        $attachments = request()->attachments;

        $fileRecords[] = [
            'job_applicant_id' => $jobApplicantId,
            'question' => null,
            'answer' => request()->apply_form_setting,
            'attachment' => null,
        ];

        if ($attachments) {
            foreach ($attachments as $attachment) {
                foreach ($attachment as $eachFile) {
                    $fileRecords[] = [
                        'job_applicant_id' => $jobApplicantId,
                        'question' => $eachFile->getClientOriginalName(),
                        'answer' => null,
                        'attachment' => $this->storeEachFile($eachFile),
                    ];
                }
            }
        }

        ApplicationAnswer::query()->insert($fileRecords);

        return $fileRecords;
    }

    private function storeEachFile($file)
    {
        if ($file) {
            return $this->withOriginalName()->storeFile($file, 'attachments');
        }

        return '';
    }
}
