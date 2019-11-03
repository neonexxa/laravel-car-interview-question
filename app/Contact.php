<?php

namespace App;

use App\Car;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
