<?php

namespace Database\Factories\App\Applicant;

use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Applicant\Note;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_applicant_id' => $this->faker->randomElement(JobApplicant::query()->pluck('id')->toArray()),
            'noted_by' => $this->faker->randomElement(User::query()->pluck('id')->toArray()),
            'description' => $this->faker->realText(),
        ];
    }
}
