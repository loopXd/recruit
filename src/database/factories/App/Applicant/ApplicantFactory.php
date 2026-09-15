<?php

namespace Database\Factories\App\Applicant;

use App\Models\App\Applicant\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Applicant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' =>  $this->faker->lastName,
            'email' =>  $this->faker->email,
            'gender' =>  $this->faker->randomElement(['male','female','other']),
            'phone' =>  $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}
