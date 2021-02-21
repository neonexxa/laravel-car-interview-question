<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Appointment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Appointment::class, function (Faker $faker) {
    return [
        'car_id' => 999,
        'workshop_id' => 999,
        'start_time' => 900,
        'end_time' => 1000,
    ];
});
