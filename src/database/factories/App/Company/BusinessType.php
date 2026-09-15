<?php

namespace Database\Factories\App\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessType extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\App\Company\BusinessType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'name' => $this->faker->countryCode,
        ];
    }
}
