<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workshop;
use App\Appointment;
use App\Services\SortLocationService;
use App\Support\Collection;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        if (!(empty($request->is_available))) {
            $workshops = Workshop::getAvailable($request)->get();
        }else{
            $workshops = Workshop::all();
        }
        
        if (!(empty($request->sortType))) {    
            foreach ($workshops as &$eachdata) {
                $eachdata->distance = SortLocationService::theGreatCircleDistance(
                    $request->latitude,
                    $request->longitude,
                    $eachdata->latitude,
                    $eachdata->longitude);
            }
            $workshops = ($request->sortType == "nearest") ? $workshops->sortBy('distance') : $workshops->sortByDesc('distance');
        }
        $data = $workshops;
        return [
            'data' => $data->values()
        ];
    }
}
