<?php

namespace Database\Seeders\App\Applicant;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CandidateSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {}

}
