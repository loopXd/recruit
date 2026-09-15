<?php

namespace Database\Seeders\App\Recruitment;

use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class JobStageSeeder extends Seeder
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
        $jobPosts = JobPost::query()->get();

        $jobStages = [];
        foreach ($jobPosts as $jobPost) {
            $stages = explode(',', $jobPost->stages);

            foreach ($stages as $stage) {

                array_push($jobStages, [
                    "job_post_id" => $jobPost->id,
                    "name" => $stage
                ]);
            }
        }

        $this->enableForeignKeys();
        JobStage::query()->insert($jobStages);
    }

}
