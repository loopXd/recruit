<?php

namespace Database\Factories\App\JobPost;

use App\Models\App\JobPost\JobType;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             'name' => $this->faker->colorName,
             'brief' => $this->faker->colorName,
        ];
    }
}
