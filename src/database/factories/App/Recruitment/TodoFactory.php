<?php

namespace Database\Factories\App\Recruitment;

use App\Models\App\Recruitment\Todo;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status_id' => $this->faker->randomElement(resolve(StatusRepository::class)->statuses('todo')->pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::query()->pluck('id')->toArray()),
            'name' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
        ];
    }
}
