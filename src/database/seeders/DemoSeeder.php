<?php

namespace Database\Seeders;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\Event;
use App\Models\App\Applicant\Note;
use App\Models\App\Company\CompanyLocation;
use App\Models\App\Company\Department;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\App\Recruitment\Todo;
use Database\Seeders\App\Applicant\AttendeeSeeder;
use Database\Seeders\App\Applicant\CandidateSeeder;
use Database\Seeders\App\Applicant\EventTypeSeeder;
use Database\Seeders\App\Applicant\JobApplicantSeeder;
use Database\Seeders\App\JobPost\ExperienceLevelSeeder;
use Database\Seeders\App\Recruitment\JobStageSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    use TruncateTable;
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        activity()->withoutLogs(function () {
            $this->call(DatabaseSeeder::class);
            $this->call(ExperienceLevelSeeder::class);
            Department::factory(6)->create();
            CompanyLocation::factory(12)->create();
            Applicant::factory(12)->create();
            JobPost::factory(50)->create();
            $this->call(JobStageSeeder::class);
            $this->call(JobApplicantSeeder::class);
            $this->call(EventTypeSeeder::class);
            Event::factory(12)->create();
            HiringTeam::factory(12)->create();
            $this->call(AttendeeSeeder::class);
            Note::factory(12)->create();
            Todo::factory(12)->create();
            $this->call(CandidateSeeder::class);
            //Meeting::factory(30)->create();

        });
    }

}
