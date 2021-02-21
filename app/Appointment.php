<?php

namespace App;

use App\Car;
use App\Workshop;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
    public function scopeJoinContactCarWorkshop($query)
    {
        return $query->orderBy('appointments.created_at', 'desc')
        ->select(
            'appointments.id', 
            'workshops.name as workshops_name', 
            'appointments.start_time', 
            'appointments.end_time', 
            'workshops.phone as workshops_phone',
            'workshops.latitude as workshops_latitude',
            'workshops.longitude as workshops_longitude',
            'contacts.name as contacts_name', 
            'contacts.phone as contacts_phone', 
            'contacts.email as contacts_email', 
            'cars.make as cars_make',
            'cars.model as cars_model',
            'cars.engine_number as cars_engine_number',
            'cars.chassis_number as cars_chassis_number',
            'cars.latitude as cars_latitude',
            'cars.longitude as cars_longitude')
            ->join('cars', 'appointments.car_id', '=', 'cars.id', 'left outer')
            ->join('contacts', 'cars.contact_id', '=', 'contacts.id', 'left outer')
            ->join('workshops', 'appointments.workshop_id', '=', 'workshops.id', 'left outer');
    }
}
