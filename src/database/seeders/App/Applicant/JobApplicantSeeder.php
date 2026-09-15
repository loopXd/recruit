<?php

namespace Database\Seeders\App\Applicant;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use App\Repositories\Core\Status\StatusRepository;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobApplicantSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $applicants = Applicant::query()->get();
        $jobPostIds = JobPost::query()->get()->pluck('id');
        $count = count($jobPostIds);
        $jobApplicants = [];

        foreach ($applicants as $applicant) {
            $end = rand(1, $count);

            for ($i = 0; $i < $end; $i++) {

                $jobPostId = $jobPostIds[$i];
                $jobStages = JobStage::query()->select('name', 'id')->where('job_post_id', $jobPostId)->get()->toArray();
                $index = array_rand($jobStages);
                $stageValue = $jobStages[$index];

                if (strtolower(trim($stageValue['name'])) === 'new') {
                    $statusName = 'status_new';
                } elseif (strtolower(trim($stageValue['name'])) === 'hired') {
                    $statusName = 'status_hired';
                } elseif (strtolower(trim($stageValue['name'])) === 'disqualified') {
                    $statusName = 'status_disqualified';
                } else {
                    $statusName = 'status_in_progress';
                }

                array_push($jobApplicants, [
                    "applicant_id" => $applicant->id,
                    "job_post_id" => $jobPostId,
                    "current_stage_id" => $stageValue['id'],
                    "status_id" => resolve(StatusRepository::class)->getStatusId('job_applicant', $statusName),
                    "slug" => (string)Str::uuid(),
                    "review" => (string)array_rand(['0', '1', '2', '3', '4', '5']),
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }
        }

        $this->enableForeignKeys();
        JobApplicant::query()->insert($jobApplicants);
    }

}
