<?php


namespace App\Services\App\Recruitment;


use App\Models\App\Recruitment\HiringTeam;
use App\Services\App\AppService;

class HiringTeamService extends AppService
{
    public function __construct(HiringTeam $team)
    {
        $this->model = $team;
    }

}