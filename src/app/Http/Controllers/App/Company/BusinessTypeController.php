<?php

namespace App\Http\Controllers\App\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Company\BusinessTypeRequest;
use App\Models\App\Company\BusinessType;
use Illuminate\Http\Request;

class BusinessTypeController extends Controller
{

    public function __construct(BusinessTypeRequest $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(Request $request)
    {
        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('business_type');
    }

    public function show(BusinessType $business_type)
    {
        return $business_type;
    }

    public function update(Request $request, BusinessType $business_type)
    {
        $this->service
            ->setModel($business_type)
            ->setAttributes($request->only('name'))
            ->save();

        return updated_responses('business_type');
    }

    public function destroy(BusinessType $business_type)
    {
        $business_type->delete();

        return deleted_responses('business_type');
    }
}