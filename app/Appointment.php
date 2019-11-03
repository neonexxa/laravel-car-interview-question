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
}
