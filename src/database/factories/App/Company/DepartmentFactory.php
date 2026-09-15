<?php

namespace Database\Factories\App\Company;

use App\Models\App\Company\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;
    private $index = -1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->index++;
        $departments = ['JavaScript','Laravel', 'Wordpress', 'QA', 'Admin', 'Management'];
        return [
            'name' => $this->index < 6 ? $departments[$this->index] : $this->faker->countryCode
        ];
    }
}
