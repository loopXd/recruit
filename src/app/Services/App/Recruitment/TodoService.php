<?php


namespace App\Services\App\Recruitment;


use App\Models\App\Recruitment\Todo;
use App\Services\App\AppService;

class TodoService extends AppService
{
    public function __construct(Todo $todo)
    {
        $this->model = $todo;
    }

}