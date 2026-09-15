<?php

namespace App\Http\Controllers\App\Company;

use App\Filters\App\JobPost\LocationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Company\CompanyLocationRequest;
use App\Models\App\Company\CompanyLocation;
use App\Services\App\Company\CompanyLocationService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyLocationController extends Controller
{
    public function __construct(CompanyLocationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(CompanyLocationRequest $request)
    {
        $this->service
            ->setAttributes($request->only('address'))
            ->save();

        return created_responses('company_location');
    }

    public function show(CompanyLocation $companyLocation): object
    {
        return $companyLocation;
    }

    public function update(CompanyLocationRequest $request, CompanyLocation $companyLocation)
    {
        $this->service
            ->setModel($companyLocation)
            ->setAttributes($request->only('address'))
            ->save();

        return updated_responses('company_location');
    }

    public function destroy(CompanyLocation $companyLocation)
    {
        $companyLocation->delete();

        return deleted_responses('company_location');
    }

    public function getAllLocations(CompanyLocation $companyLocation)
    {
        return $companyLocation->all();
    }
    public function getAllLocationsBySearch(Request $request)
    {
        $selected = collect([]);
        $ids = [];
        if ($request->selected) {
            $ids = array_map(function ($id) {
                return (int)$id;
            }, (explode(',', $request->selected)));
            $selected = CompanyLocation::query()->whereIn('id', $ids)->get();
        }

        $data = CompanyLocation::query()
            ->whereNotIn('id', $ids)
            ->filters(new LocationFilter())
            ->paginate(request('per_page', 10));
        return new LengthAwarePaginator(array_merge($selected->toArray(), $data->getCollection()->toArray()), $data->total(), request('per_page', 10), $data->currentPage(), ['path' => $data->path()]);
    }
}
