<?php

use Illuminate\Database\Seeder;

class AppointmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Appointment::create([
            'car_id' => 1,
            'workshop_id' => 3,
            'start_time' => 1600,
            'end_time' => 1700,
        ]);
        \App\Appointment::create([
            'car_id' => 2,
            'workshop_id' => 2,
            'start_time' => 1200,
            'end_time' => 1400,
        ]);
        \App\Appointment::create([
            'car_id' => 1,
            'workshop_id' => 1,
            'start_time' => 900,
            'end_time' => 1100,
        ]);
        \App\Appointment::create([
            'car_id' => 2,
            'workshop_id' => 3,
            'start_time' => 1400,
            'end_time' => 1500,
        ]);
    }
}
