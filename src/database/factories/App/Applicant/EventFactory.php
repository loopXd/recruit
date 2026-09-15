<?php

namespace Database\Factories\App\Applicant;

use App\Models\App\Applicant\Event;
use App\Models\App\Applicant\EventType;
use App\Models\App\Applicant\JobApplicant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'event_type_id' => $this->faker->randomElement(EventType::query()->pluck('id')->toArray()),
            'job_applicant_id' =>  $this->faker->randomElement(JobApplicant::query()->pluck('id')->toArray()),
            'location' =>  $this->faker->city,
            'start_at' =>  $this->faker->dateTime(),
            'end_at' => $this->faker->dateTime(),
            'description' => $this->faker->realText(),
        ];
    }
}
