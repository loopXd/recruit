<?php

namespace Database\Factories\App\Recruitment;

use App\Models\App\Applicant\Event;
use App\Models\App\Recruitment\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_id' => $this->faker->randomElement(Event::query()->pluck('id')->toArray()),
            'meeting_id' => $this->faker->numberBetween(1000, 2000),
            'topic' => $this->faker->title,
            'duration' => 15,
            'password' => Hash::make('123456'),
            'start_url' => $this->faker->url,
            'join_url' => $this->faker->url,
            'meeting_channel' => 'zoom',
        ];
    }
}
