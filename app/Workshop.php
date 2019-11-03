<?php

namespace App;

use App\Appointment;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    //
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
