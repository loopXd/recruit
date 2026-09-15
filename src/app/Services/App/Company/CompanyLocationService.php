<?php


namespace App\Services\App\Company;


use App\Models\App\Company\CompanyLocation;
use App\Services\App\AppService;

class CompanyLocationService extends AppService
{
    public function __construct(CompanyLocation $companyLocation)
    {
        $this->model = $companyLocation;
    }
}