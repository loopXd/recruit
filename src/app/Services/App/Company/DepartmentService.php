<?php

namespace App\Services\App\Company;

use App\Models\App\Company\Department;
use App\Services\App\AppService;

/**
 * @method latest()
 */
class DepartmentService extends AppService
{
    public function __construct(Department $department)
    {
        $this->model = $department;
    }
}