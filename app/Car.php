<?php

namespace App;

use App\Appointment;
use App\Contact;
use Illuminate\Database\Eloquent\Model;


class Car extends Model
{
    //
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
