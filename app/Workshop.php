<?php

namespace App;

use App\Appointment;
use App\Services\SortLocationService;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    //
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function scopeGetAvailable($query,$request)
    {
        return $query->where('workshops.opening_time', '<=', $request->start_time )
                ->where('workshops.closing_time', '>=', $request->end_time )
                ->whereHas('appointments', function($qr) use($request){
                    $qr->where(function ($qr2) use ($request) {
                        $qr2->where('start_time', '=', $request->start_time);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('end_time', '=', $request->end_time);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->start_time);
                        $qr2->where('end_time', '>', $request->start_time);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->end_time);
                        $qr2->where('end_time', '>', $request->end_time);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->start_time);
                        $qr2->where('end_time', '>', $request->end_time);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '>', $request->start_time);
                        $qr2->where('end_time', '<', $request->end_time);
                    });
                }, '<', 1);
    }
}
