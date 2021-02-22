<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\Workshop;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::joinContactCarWorkshop();
        
        if (!(empty($request->workshop_ids))) {
            $query->whereIn('appointments.workshop_id', explode(",", $request->workshop_ids));
        }
        $per_page = empty($request->per_page) ? 30 : $request->per_page;

        $pager = (!empty($request->is_complete_pagination)) ? 'paginate' : 'simplePaginate';
        $data = (!empty($request->is_disable_pagination)) ? $query->get() : $query->$pager($per_page);
        
        return [
            'data' => $data
        ];
    }

    public function store(Request $request)
    {
        if(Workshop::getAvailable($request)->count()){
            $data = Appointment::create([
                'workshop_id' => $request->workshop_id,
                'car_id' => $request->car_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        }else{
            $data = [
                'error' => 'no_available_slot',
                'message' => 'Fail to create appointment' ,
            ];
        }

        return [
            'data' => $data
        ];
    }
}
