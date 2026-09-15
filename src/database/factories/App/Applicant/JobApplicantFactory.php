<?php

namespace Database\Factories\App\Applicant;

use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use App\Models\App\Recruitment\Stage;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobApplicant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             'applicant_id' => $this->faker->randomElement(Applicant::query()->pluck('id')->toArray()),
             'job_post_id' => $this->faker->randomElement(JobPost::query()->pluck('id')->toArray()),
             'current_stage_id' => $this->faker->randomElement(JobStage::query()->pluck('id')->toArray()),
             'status_id' => $this->faker->randomElement(resolve(StatusRepository::class)->statuses('job_applicant')->pluck('id')->toArray()),
             'slug' => $this->faker->uuid,
             'review' => $this->faker->randomElement(['0', '1']),
        ];
    }
}
