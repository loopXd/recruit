<?php

namespace App\Http\Controllers\App\JobPost;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\JobPost\ExperienceRequest;
use App\Models\App\JobPost\ExperienceLevel;
use App\Services\App\JobPost\ExperienceService;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function __construct(ExperienceService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(ExperienceRequest $request): array
    {
        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('experience_level');
    }

    public function show(ExperienceLevel $experienceLevel): object
    {
        return $experienceLevel;
    }

    public function update(Request $request, ExperienceLevel $experienceLevel)
    {
        $this->service
            ->setModel($experienceLevel)
            ->setAttrs($request->only('name'))
            ->validations()
            ->update();

        return updated_responses('experience_level');
    }

    public function destroy(ExperienceLevel $experienceLevel)
    {


        throw_if(
            $experienceLevel->jobPost()->exists(),
            new GeneralException(__t('experience_level_already_used'))
        );
        $experienceLevel->delete();

        return deleted_responses('experience_level');
    }

    public function getExperiences(ExperienceLevel $experienceLevel)
    {
        return $experienceLevel->all();
    }
}
