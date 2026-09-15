<?php


namespace App\Http\Controllers\App\Export;


use App\Export\AllCandidateExport;
use App\Filters\App\Applicant\ApplicantFilter;
use App\Http\Controllers\Controller;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Recruitment\HiringTeam;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\Applicant\ApplicantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

class ApplicantExportController extends Controller
{
    private const CITY_FIELD_IDS = ['city', 'cidade'];

    public function __construct(ApplicantService $service, ApplicantFilter $applicantFilter)
    {
        $this->service = $service;
        $this->filter = $applicantFilter;
    }

    public function exportCandidate(Request $request, Applicant $applicant)
    {
        $applicants = $this->service
            ->with(
                [
                    'totalApplication',
                    'jobApplicants' => function ($q) {
                        $this->filter
                            ->appliedDate($q)
                            ->applicantJobPost($q)
                            ->jobApplicantStatus($q)
                            ->jobApplicantReview($q);
                    },
                    'jobApplicants.jobPost',
                    'jobApplicants.currentStage',
                    'jobApplicants.status',
                ]
            )
            ->whereHas('jobApplicants', function ($query) {
                $query->when(! request()->get('status'), function ($query) {
                    $query->where('status_id', '!=', resolve(StatusRepository::class)
                        ->getStatusId('job_applicant', 'status_disqualified'));
                }, function ($query) {
                    $id = request()->get('status');
                    $query->where('status_id', intval($id));
                });

                if (auth()->user()->can('can_view_applicant')) {
                    if (! auth()->user()->isAppAdmin() && ! auth()->user()->isAppManager()) {
                        $user_id = auth()->user()->id;
                        $jobs = HiringTeam::where('recruiter_id', $user_id)->pluck('job_post_id')->toArray();
                        $query->whereIn('job_post_id', $jobs);
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

                $query->when(request()->get('review'), function ($query) {
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
                $this->applyCityFilter($query, request()->get('city'));
            })
            ->when(request()->get('search'), function ($query) {
                $value = request()->get('search');
                $query->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
                    ->orWhere('email', $value);
            })
            ->when(request()->get('search'), function ($query) {
                $query->orderBy(DB::raw("CONCAT(first_name,' ',last_name)"));
            }, function ($query) {
                $query->latest();
            })->get();

        $within = '';
        $file_name = 'all-applicant-list-' . Str::of($within ?: \request('month'))->kebab() . '.xlsx';

        return (new AllCandidateExport($applicants))->download($file_name, Excel::XLSX);
    }

    public function exportAllCandidate(Request $request, Applicant $applicant)
    {
       $applicants = $this->service
            ->with(
                [
                    'totalApplication',
                    'jobApplicants' => function ($q) {
                        $this->filter
                            ->appliedDate($q)
                            ->applicantJobPost($q)
                            ->jobApplicantStatus($q)
                            ->jobApplicantReview($q);
                    },
                    'jobApplicants.jobPost',
                    'jobApplicants.currentStage',
                    'jobApplicants.status',
                ]
            )
            ->whereHas('jobApplicants', function ($query) {
                $query->when(! request()->get('status'), function ($query) {
                    $query->where('status_id', '!=', resolve(StatusRepository::class)
                        ->getStatusId('job_applicant', 'status_disqualified'));
                }, function ($query) {
                    $id = request()->get('status');
                    $query->where('status_id', intval($id));
                });

                if (auth()->user()->can('can_view_applicant')) {
                    if (! auth()->user()->isAppAdmin() && ! auth()->user()->isAppManager()) {
                        $user_id = auth()->user()->id;
                        $jobs = HiringTeam::where('recruiter_id', $user_id)->pluck('job_post_id')->toArray();
                        $query->whereIn('job_post_id', $jobs);
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

                $query->when(request()->get('review'), function ($query) {
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
                $this->applyCityFilter($query, request()->get('city'));
            })
            ->when(request()->get('search'), function ($query) {
                $value = request()->get('search');
                $query->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
                    ->orWhere('email', $value);
            })
            ->when(request()->get('search'), function ($query) {
                $query->orderBy(DB::raw("CONCAT(first_name,' ',last_name)"));
            }, function ($query) {
                $query->latest();
            })->get();

        $within = '';
        $file_name = 'all-applicant-list-' . Str::of($within ?: \request('month'))->kebab() . '.xlsx';

        return (new AllCandidateExport($applicants))->download($file_name, Excel::XLSX);

    }

    private function applyCityFilter($query, string $city): void
    {
        $query->where(function ($query) use ($city) {
            // Fonte 1: perfil do usuário vinculado ao candidato. "address" é uma
            // coluna de texto simples (não JSON), então usamos LIKE direto.
            $query->whereHas('profile', function ($profile) use ($city) {
                $profile->where('address', 'LIKE', "%{$city}%");
            })
            // Fonte 2 (principal): job_applicants.apply_form_setting, onde fica
            // salvo o formulário de candidatura completo (inclui endereço/cidade).
            ->orWhereHas('jobApplicants', function ($jobApplicant) use ($city) {
                $jobApplicant->where(function ($fields) use ($city) {
                    foreach (self::CITY_FIELD_IDS as $fieldId) {
                        $fields->orWhereRaw($this->cityJsonSearchSql(), [$fieldId, "%{$city}%"]);
                    }
                });
            });
        });
    }

    /**
     * Ver comentário equivalente em ApplicantController::cityJsonSearchSql().
     * A busca precisa mirar `apply_form_setting` (job_applicants), não a
     * tabela `application_answers`, que guarda apenas respostas avulsas de
     * perguntas customizadas e não o formulário de candidatura inteiro.
     */
    private function cityJsonSearchSql(): string
    {
        // Ver comentário equivalente em ApplicantController::cityJsonSearchSql()
        // sobre por que o COLLATE é necessário.
        return "
            JSON_VALID(apply_form_setting)
            AND (JSON_UNQUOTE(
                JSON_EXTRACT(
                    apply_form_setting,
                    REPLACE(
                        JSON_UNQUOTE(JSON_SEARCH(
                            apply_form_setting,
                            'one',
                            ?,
                            NULL,
                            '$[*].items[*].fields[*].fields[*].id'
                        )),
                        '.id',
                        '.value'
                    )
                )
            ) COLLATE utf8mb4_unicode_ci) LIKE ?
        ";
    }

}