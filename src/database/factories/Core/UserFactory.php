<?php

use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;

$factory->define(User::class, function (Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret',
        'status_id' => resolve(StatusRepository::class)->userActive(),
        'is_in_employee' => 1
    ];
});
