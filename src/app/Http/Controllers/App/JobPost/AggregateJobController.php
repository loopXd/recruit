<?php

namespace App\Http\Controllers\App\JobPost;

use App\Exceptions\GeneralException;
use App\Filters\App\JobPost\JobPostFilter;
use App\Helpers\App\AppOnDeleteRelatedModels;
use App\Helpers\traits\SiteMapUpdateHelper;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Recruitment\JobStage;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Status;
use App\Models\App\JobPost\JobPost;
use App\Http\Controllers\Controller;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\App\JobPost\JobPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AggregateJobController extends Controller
{
    use SiteMapUpdateHelper;

    protected $service;
    protected $filter;

    public function __construct(JobPostService $service, JobPostFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    /**
     * Verifica se o usuário autenticado tem acesso ao job post
     *
     * @param JobPost $jobPost
     * @param string|array $permissions
     * @return bool
     */
    private function hasAccessToJob(JobPost $jobPost, $permissions): bool
    {
        $user = auth()->user();

        if ($user->isAppAdmin() || $user->isAppManager()) {
            return true;
        }

        $permissions = (array) $permissions;

        $hasPermission = false;
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            return false;
        }

        // Verifica se o usuário está no time recrutador desse job post
        return $hasPermission || $jobPost->recruiters()->where('recruiter_id', $user->id)->exists();
    }

    //--------------------AGGREGATE METHODS--------------------------------------------

    public function summery()
    {
        return $this->service
            ->filters($this->filter)
            ->select(
                'id',
                'company_location_id',
                'posted_by',
                'job_type_id',
                'status_id',
                'department_id',
                'experience_level_id',
                'name',
                'working_arrangement',
                'slug',
                'created_at',
                'stages',
                'salary',
                'is_viewable',
                'vacancy_count',
                'last_submission_date'
            )
            ->with([
                'location:id,address',
                'jobType:id,name',
                'experienceLevel:id,name',
                'department:id,name',
                'status:id,name,class,type',
                'postedBy:id,first_name,last_name,email',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with(['jobApplicantCount']);
                },
                'totalApplicants',
            ])
            ->when(auth()->user()->hasRole('Candidate') || !request()->get('status'), function ($query) {
                $query->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'));
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function viewOverview(JobPost $job_post)
    {
        if (!$this->hasAccessToJob($job_post, ['can_view_job_post', 'can_view_job_overview'])) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        return view('dashboard.job-overview', ['job_post_id' => $job_post->id]);
    }

    public function overview(JobPost $job_post)
    {
        if (!$this->hasAccessToJob($job_post, ['can_view_job_post', 'can_view_job_overview'])) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        return JobPost::select(
            'id',
            'company_location_id',
            'posted_by',
            'job_type_id',
            'status_id',
            'name',
            'slug',
            'created_at',
            'stages',
            'working_arrangement',
            'experience_level_id'
        )
        ->with([
            'location:id,address',
            'experienceLevel:id,name',
            'jobType:id,name',
            'status:id,name,class,type',
            'postedBy:id,first_name,last_name,email',
            'jobStages' => function ($query) {
                $query->select('id', 'name', 'job_post_id')
                    ->with([
                        'jobApplicantCount',
                        'jobApplicants.appliedBy:id,first_name,last_name,email',
                        'jobApplicants.status:id,name,class,type'
                    ]);
            },
            'totalApplicants',
        ])
        ->find($job_post->id);
    }

    public function applicants(Applicant $applicant, $jobPostId)
    {
        $jobPostId = intval($jobPostId);
        if ($jobPostId < 1) {
            return [];
        }

        $job = JobPost::find($jobPostId);
        if (!$job || !$this->hasAccessToJob($job, 'can_view_job_post')) {
            return response()->json(['message' => trans('default.action_not_allowed')], 403);
        }

        return $applicant
            ->with([
                'jobApplicants' => function ($query) use ($jobPostId) {
                    $query->where('job_post_id', $jobPostId);
                },
                'jobApplicants.currentStage',
                'jobApplicants.status',
                'totalApplication'
            ])
            ->whereHas('jobApplicants', function ($query) use ($jobPostId) {
                $query->where('job_post_id', $jobPostId);
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function redirectUrl($job_slug)
    {
        return redirect(route('public.jobPost.show_pob_post', ['job_slug' => $job_slug]));
    }

    public function showJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
    {
        $status = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();

        $job = $jobPost
            ->select(
                'id',
                'slug',
                'name',
                'description',
                'job_type_id',
                'company_location_id',
                'job_post_settings',
                'status_id',
                'last_submission_date',
                'vacancy_count',
                'salary',
                'department_id',
                'is_viewable',
                'working_arrangement',
                'experience_level_id'
            )
            ->with([
                'jobPostThumbnail',
                'status:id,name,type',
                'experienceLevel:id,name',
                'jobType:id,name',
                'department:id,name',
                'location:id,address',
                'totalApplicants'
            ])
            ->where('slug', $jobPostSlug)
            ->first();

        if ($job) {
            $response = $job;
            $response['salary'] = number_format($response['salary'], 2, ',', '.');

            if (auth()->id() === null && ($job->last_submission_date < date("Y-m-d") || intval($job->status_id) != intval($status->id))) {
                return view('custom_errors.404', ['message' => __t('job_post_closed')]);
            }

        } else {
            return view('custom_errors.404', ['message' => __t('no_job_post_found')]);
        }

        $applyRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);
        $viewRoute = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);

        return view('candidates.job-post', ['response' => $response, 'applyLink' => $applyRoute, 'viewLink' => $viewRoute]);
    }

public function applyJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
{
    if (empty(auth()->id())) {
        return redirect()->route('users.login', ['redirect' => $jobPostSlug]);
    }

    $status = $status->where('type', 'job_post')
        ->where('name', 'status_open')
        ->firstOrFail();

    $job = $jobPost->with('jobPostThumbnail', 'location:id,address')
        ->select(
            'id',
            'slug',
            'name',
            'description',
            'job_type_id',
            'company_location_id',
            'apply_form_settings',
            'status_id',
            'last_submission_date',
            'vacancy_count'
        )
        ->where('slug', $jobPostSlug)
        ->first();

    if (!$job) {
        return view('custom_errors.404', ['message' => __t('no_job_post_found')]);
    }

    if ($job->last_submission_date < now()->format('Y-m-d') || $job->status_id != $status->id) {
        return view('custom_errors.404', ['message' => __t('job_post_closed')]);
    }

    $profile = Profile::where('user_id', auth()->id())->first();
    $lastApply = $profile ? json_decode($profile->apply_form_setting) : null;

    // 1. Criar um mapa de dados do perfil para acesso rápido
    $profileDataMap = [];
    if ($lastApply) {
        $this->buildDataMapRecursive($lastApply, $profileDataMap);
    }

    // 2. Decodificar as configurações do formulário de aplicação da vaga
    $jobApplyFormSettings = is_string($job->apply_form_settings)
        ? json_decode($job->apply_form_settings)
        : $job->apply_form_settings;

    // 3. Preencher o formulário da vaga com os dados do mapa
    if (!empty($profileDataMap) && $jobApplyFormSettings) {
        $this->fillFormRecursive($jobApplyFormSettings, $profileDataMap);
    }

    $job->apply_form_settings = $jobApplyFormSettings;

    $viewRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);

    return view('candidates.apply-form', ['response' => $job, 'viewLink' => $viewRoute]);
}

/**
 * Constrói um mapa de dados de forma recursiva a partir do perfil do usuário.
 * Usa chaves únicas baseadas no contexto para evitar duplicação.
 *
 * @param mixed $data Os dados do perfil a serem percorridos
 * @param array $map O array de mapa a ser preenchido (passado por referência)
 * @param string $context Contexto atual para criar chaves únicas
 */
private function buildDataMapRecursive($data, array &$map, $context = '')
{
    if (is_array($data)) {
        foreach ($data as $index => $item) {
            $newContext = $context . ($context ? '.' : '') . $index;
            $this->buildDataMapRecursive($item, $map, $newContext);
        }
    } elseif (is_object($data)) {
        // Se tem title e value, pode ser uma seção específica
        if (isset($data->title) && property_exists($data, 'value') && 
            $data->value !== null && $data->value !== '' && $data->value !== []) {
            
            // Criar chave única baseada no contexto + título
            $uniqueKey = $context . '.' . $data->title;
            $map[$uniqueKey] = $data->value;
        }
        
        // Se o objeto tem 'id' e 'value', adiciona ao mapa (apenas se não estiver vazio)
        if (isset($data->id) && property_exists($data, 'value') && 
            $data->value !== null && $data->value !== '' && $data->value !== []) {
            $map[$data->id] = $data->value;
        }
        
        // Percorre todas as propriedades do objeto
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $newContext = $context . ($context ? '.' : '') . $key;
                $this->buildDataMapRecursive($value, $map, $newContext);
            }
        }
    }
}

/**
 * Preenche a estrutura do formulário de forma recursiva.
 * Usa chaves únicas baseadas no contexto para preenchimento preciso.
 *
 * @param mixed $form O formulário ou sub-estrutura a ser preenchida (passado por referência)
 * @param array $dataMap O mapa de dados do perfil do usuário
 * @param string $context Contexto atual para criar chaves únicas
 */
private function fillFormRecursive(&$form, array $dataMap, $context = '')
{
    if (is_array($form)) {
        foreach ($form as $index => &$item) {
            $newContext = $context . ($context ? '.' : '') . $index;
            $this->fillFormRecursive($item, $dataMap, $newContext);
        }
    } elseif (is_object($form)) {
        // Se o objeto tem ID e existe no mapa de dados, preenche o valor
        if (isset($form->id) && array_key_exists($form->id, $dataMap)) {
            $value = $dataMap[$form->id];
            // Apenas preenche se o valor não estiver vazio
            if ($value !== null && $value !== '' && $value !== []) {
                // Garante que a propriedade 'value' existe
                if (!property_exists($form, 'value')) {
                    $form->value = null;
                }
                // Preenche com o valor do mapa de dados
                $form->value = $value;
            }
        }
        
        // Para campos custom-form que usam 'title' como identificador
        if (isset($form->title)) {
            // Primeiro tenta com a chave única (contexto + título)
            $uniqueKey = $context . '.' . $form->title;
            
            if (array_key_exists($uniqueKey, $dataMap)) {
                $value = $dataMap[$uniqueKey];
                // Apenas preenche se o valor não estiver vazio
                if ($value !== null && $value !== '' && $value !== []) {
                    // Garante que a propriedade 'value' existe
                    if (!property_exists($form, 'value')) {
                        $form->value = null;
                    }
                    // Preenche com o valor do mapa de dados
                    $form->value = $value;
                }
            }
            // Se não encontrou com chave única, tenta com título simples (fallback)
            elseif (array_key_exists($form->title, $dataMap)) {
                $value = $dataMap[$form->title];
                // Apenas preenche se o valor não estiver vazio
                if ($value !== null && $value !== '' && $value !== []) {
                    // Garante que a propriedade 'value' existe
                    if (!property_exists($form, 'value')) {
                        $form->value = null;
                    }
                    // Preenche com o valor do mapa de dados
                    $form->value = $value;
                }
            }
        }
        
        // Percorre todas as propriedades do objeto recursivamente
        foreach ($form as $key => &$value) {
            if (is_array($value) || is_object($value)) {
                $newContext = $context . ($context ? '.' : '') . $key;
                $this->fillFormRecursive($value, $dataMap, $newContext);
            }
        }
    }
}

/**
 * Método adicional para debug - mostra o mapa de dados construído
 * (Remover em produção)
 */
private function debugDataMap(array $dataMap)
{
    \Log::info('Data Map construído:', $dataMap);
}

/**
 * Verifica se um valor está vazio (null, string vazia, array vazio)
 *
 * @param mixed $value
 * @return bool
 */
private function isEmptyValue($value)
{
    return $value === null || $value === '' || $value === [] || 
           (is_string($value) && trim($value) === '');
}

    public function viewSetting($jobPostId)
    {
        return view('dashboard.job-settings', ['job_post_id' => $jobPostId]);
    }

    public function editJobPost(JobPost $job)
    {
        $previewLink = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
        return view('dashboard.job-editor', ['job' => $job->load('department:id,name', 'jobType:id,name', 'experienceLevel:id,name'), 'previewLink' => $previewLink]);
    }

    public function updateJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $this->service
            ->setModel($job)
            ->save($request->only(['job_post_settings', 'name', 'description', 'vacancy_count']));

        $this->notifyGoogleIndex($job, $job->status->name === 'status_closed' ? 'delete' : 'update');
        $this->updateSiteMap();

        return $job->wasChanged() ? updated_responses('job_post_template') : nothing_to_update_response(__t('nothing_to_update'), ['job_post_settings' => $request->job_post_settings]);
    }

    public function publishJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $input = [];
        $input['job_post_settings'] = $request->job_post_settings;
        $input['status_id'] = resolve(StatusRepository::class)->getStatusId('job_post', 'status_open');

        $this->service
            ->setModel($job)
            ->save($input);

        return $job->wasChanged() ? updated_responses('job_post_template') : failed_responses(['job_post_settings' => $request->job_post_settings]);
    }

    public function editApplyForm(Request $request, JobPost $job)
    {
        $request->validate([
            'apply_form_settings' => 'required|array'
        ]);
        $this->service
            ->setModel($job)
            ->save($request->only(['apply_form_settings']));

        return $job->wasChanged() ? updated_responses('apply_form_updated') : failed_responses(['apply_form_settings' => $request->apply_form_settings]);
    }

    public function sharableLink(JobPost $job): string
    {
        return route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
    }

    public function activateJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_activated');
    }

    public function closeJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_closed')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_close');
    }

    public function bulkAssignJobs(Request $request): array
    {
        $jobPostId = $request->job_post_id;
        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $jobPostId)->first();

        $attachableIds = $request->is_all_selected ? Applicant::query()->get()->pluck('id')->toArray() : $request->attachable_ids;

        $assignableJobApplicant = collect($attachableIds)->map(function ($applicantId) use ($jobPostId, $stage) {
            $jobApplicant = JobApplicant::query()->where('job_post_id', $jobPostId)
                ->where('applicant_id', $applicantId)->exists();

            if (!$jobApplicant) {
                return [
                    'applicant_id' => $applicantId,
                    'job_post_id' => $jobPostId,
                    'current_stage_id' => optional($stage)->id,
                    'apply_form_setting' => '',
                    'status_id' => app(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
                    'slug' => Str::uuid(),
                ];
            }

            return null;
        })->filter()->toArray();

        JobApplicant::query()->insert($assignableJobApplicant);

        return custom_response('bulk_candidate_has_been_assigned_to_the_job');
    }

    public function bulkRetractJobs(Request $request, AppOnDeleteRelatedModels $model)
    {
        $attachableIds = $request->is_all_selected ? Applicant::query()->get()->pluck('id')->toArray() : $request->attachable_ids;

        $jobApplicants = JobApplicant::query()
            ->whereIn('applicant_id', $attachableIds)
            ->where('job_post_id', $request->job_post_id)
            ->get();

        foreach ($jobApplicants as $jobApplicant) {

            $model = $model->setModel($jobApplicant);
            $model->loadRelatedModelsOnDeleteJobApplicant();
            $model->removeData();
            $jobApplicant->delete();
        }

        return deleted_responses('job_applicant');
    }
}
