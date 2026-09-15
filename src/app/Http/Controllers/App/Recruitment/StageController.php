<?php

namespace App\Http\Controllers\App\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Recruitment\StageRequest;
use App\Models\App\Recruitment\Stage;
use App\Services\App\Recruitment\StageService;

class StageController extends Controller
{
    public function __construct(StageService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(StageRequest $request): array
    {
        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('stage');
    }

    public function show(Stage $stage): object
    {
        return $stage;
    }

    public function update(StageRequest $request, Stage $stage)
    {
        $this->service
            ->setModel($stage)
            ->setAttributes($request->only('name'))
            ->save();

        return updated_responses('stage');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return deleted_responses('stage');
    }
}
