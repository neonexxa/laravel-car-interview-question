<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::orderBy('appointments.created_at', 'desc')
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
}
