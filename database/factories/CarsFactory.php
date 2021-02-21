<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Car;
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

$factory->define(Car::class, function (Faker $faker) {
    return [
        'contact_id' => 999,
        'make' => 'car_make',
        'model' => 'car_model',
        'engine_number' => $faker->randomNumber,
        'chassis_number' => $faker->randomNumber,
        'latitude' => $faker->latitude($min = -90, $max = 90),
        'longitude' => $faker->longitude($min = -180, $max = 180),
    ];
});
