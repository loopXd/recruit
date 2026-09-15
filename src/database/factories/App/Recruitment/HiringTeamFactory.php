<?php

namespace Database\Factories\App\Recruitment;

use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HiringTeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HiringTeam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recruiter_id' => $this->faker->randomElement(User::query()->pluck('id')->toArray()),
            'job_post_id' => $this->faker->randomElement(JobPost::query()->pluck('id')->toArray()),
        ];
    }
}
