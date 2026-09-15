<?php

namespace App\Http\Controllers\App\Applicant;

use Google\Service\AdMob\App;
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

class ApplicantController extends Controller
{
    private const CITY_FIELD_IDS = ['city', 'cidade'];

    public function __construct(ApplicantService $service, ApplicantFilter $applicantFilter)
    {
        $this->service = $service;
        $this->filter = $applicantFilter;
    }

    public function index()
    {
        return $this->service
            ->with(
                [
                    'jobApplicants' => function ($q) {
                        $this->filter
                            ->appliedDate($q)
                            ->applicantJobPost($q)
                            ->jobApplicantStatus($q)
                            ->jobApplicantReview($q);
                    },
                    'jobApplicants.jobPost:id,name,slug,vacancy_count,status_id,last_submission_date,working_arrangement',
                    'jobApplicants.currentStage:id,job_post_id,name',
                    'jobApplicants.status:id,name,class,type',
                ]
            )
            ->withCount('jobApplicants as total_application')
            ->whereHas('jobApplicants', function ($query) {
                $query->when(!request()->get('status'), function ($query) {
                    $query->where('status_id', '!=', resolve(StatusRepository::class)
                        ->getStatusId('job_applicant', 'status_disqualified'));
                }, function ($query) {
                    $id = request()->get('status');
                    $query->where('status_id', intval($id));
                });

                if (auth()->user()->can('can_view_applicant')) {
                    if (auth()->user()->hasRole('Candidate')) {
                        auth()->user()->load('applicant');
                        $query->where('applicant_id', auth()->user()->applicant->id ?? null);
                    } else if (!auth()->user()->isAppAdmin() && !auth()->user()->isAppManager()) {
                        $query->whereHas('recruiters', function ($qIn) {
                            $qIn->where('recruiter_id', auth()->id());
                        });
                    } else {
                        $query->whereRaw('true');
                    }
                } else {
                    $query->whereRaw('false');
                }

                $query->when(request()->get('job'), function ($query) {
                    $id = request()->get('job');
                    $query->where('job_post_id', intval($id));
                });

                $query->when((isset(request()['review']) && request()->get('review') !== null), function ($query) {
                    $query->where('review', request()->get('review'));
                });

                $query->when(request()->get('applied_date_range'), function ($query) {
                    $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);
                    $query->when($date, function ($q) use ($date) {
                        $q->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
                    });
                });
            })
            ->when(request()->get('gender'), function ($query) {
                $query->where('gender', request()->get('gender'));
            })
            ->when(request()->get('city'), function ($query) {
                $city = request()->get('city');
                $query->where(function ($query) use ($city) {
                    // Fonte 1: perfil do usuário vinculado ao candidato (quando existe conta/login)
                    $query->whereHas('profile', function ($q) use ($city) {
                        $q->where('address->city', 'LIKE', "%{$city}%");
                    })
                    // Fonte 2: respostas do formulário dinâmico de aplicação (fonte principal hoje)
                    ->orWhereHas('jobApplicants.answers', function ($q) use ($city) {
                        $q->where(function ($query) use ($city) {
                            foreach (self::CITY_FIELD_IDS as $fieldId) {
                                $query->orWhereRaw($this->cityJsonSearchSql(), [$fieldId, "%{$city}%"]);
                            }
                        });
                    });
                });
            })
            ->when(request()->get('search'), function ($query) {
                $value = request()->get('search');
                $query->where(function ($qIn) use ($value) {
                    $qIn->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
            })
            ->when(request()->get('search'), function ($query) {
                $query->orderBy(DB::raw("CONCAT(first_name,' ',last_name)"));
            }, function ($query) {
                $query->latest();
            })
            ->paginate(request()->get('per_page', 10));
    }

    /**
    * Monta a expressão SQL que localiza o valor do campo de cidade no JSON
    * do formulário. O caminho cobre a estrutura atual: sections > items >
    * fields > fields, sem depender do wildcard recursivo do MariaDB.
     *
     * O JSON de `answer` é um array dinâmico de seções/itens/campos
     * (form builder), então o caminho até a cidade não é fixo. O JSON_SEARCH
     * encontra o id e o JSON_EXTRACT lê o value do mesmo objeto.
     */
    private function cityJsonSearchSql(): string
    {
        return "
            JSON_VALID(answer)
            AND JSON_UNQUOTE(
                JSON_EXTRACT(
                    answer,
                    REPLACE(
                        JSON_UNQUOTE(JSON_SEARCH(
                            answer,
                            'one',
                            ?,
                            NULL,
                            '$[*].items[*].fields[*].fields[*].id'
                        )),
                        '.id',
                        '.value'
                    )
                )
            ) LIKE ?
        ";
    }

    public function store(ApplicantRequest $request): array
    {

        $result = $this->service
            ->setAttributes($request->only(
                'first_name',
                'last_name',
                'email',
                'gender',
                'phone',
                'date_of_birth'
            ))->save();

        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $request->job_post_id)->first();

        if ($result) {
            $apply_form_setting = JobPost::find($request->job_post_id);
            $jobApplicant = JobApplicant::query()->create([
                'applicant_id' => $result->id,
                'job_post_id' => $request->job_post_id,
                'current_stage_id' => $stage->id ?? null,
                'apply_form_setting' => $apply_form_setting->apply_form_settings,
                'status_id' => resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
                'slug' => Str::uuid(),
            ]);

            //Store to timeline
            $description = trans('default.timeline_for_applied_job_by_system');
            $find = ['{candidate_name}', '{job_post_name}', '{user_name}'];
            $jobPost = JobPost::find($request->job_post_id);
            $replace = [$jobApplicant->appliedBy->full_name, $jobPost->name, auth()->user()->full_name];
            $description = str_replace($find, $replace, $description);
            log_to_database($description, [], 'timeline', auth()->user(), $jobApplicant);

            if ($request->hasFile('resume'))
                $this->service->setModel($result)->uploadResume($request->resume);

            return created_responses('applicant');
        }

        return custom_failed_response('candidate_already_exist');
    }

    public function show(Applicant $applicant): object
    {
        return $this->service
            ->with(
                'jobApplicants',
                'jobApplicants.jobPost',
                'jobApplicants.currentStage',
                'jobApplicants.status',
                'jobApplicants.answers'

            )->where('id', $applicant->id)->first();
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $result = $this->service
            ->setModel($applicant)
            ->save(
                $request->only(
                    'first_name',
                    'last_name',
                    'email',
                    'gender',
                    'phone',
                    'date_of_birth'
                )
            );

        if ($request->hasFile('resume'))
            $this->service->setModel($result)->uploadResume($request->resume);

        return updated_responses('applicant');
    }

    public function destroy(Applicant $applicant, AppOnDeleteRelatedModels $model)
    {
        $model = $model->setModel($applicant);
        $data = $model->loadRelatedModelsOnDeleteApplicant();
        $model->removeData();

        $applicant->delete();

        return deleted_responses('applicant');
    }

    //-------------Aggregate Function -----------------------

    public function checkEmail(Request $request, Applicant $applicant)
    {
        $request->validate([
            'email' => 'required | email',
        ]);

        return $applicant->where('email', $request->email)->first();
    }

    public function lastAppliedJob()
    {
        $applicant = Applicant::query()->where('email', auth()->user()->email)->first();

        return JobApplicant::query()
            ->where('applicant_id',$applicant)
            ->select('id', 'job_post_id')
            ->with('jobPost:id,job_type_id,name,created_at', 'jobPost.jobType:id,name', 'jobPost.location:id,address')
            ->latest()
            ->first();
    }
    //---------------------------------------------------------
}