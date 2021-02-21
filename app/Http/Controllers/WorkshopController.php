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
            $query  = Workshop::where('workshops.opening_time', '<=', $request->fromTime )
                ->where('workshops.closing_time', '>=', $request->toTime )
                ->whereHas('appointments', function($qr) use($request){
                    $qr->where(function ($qr2) use ($request) {
                        $qr2->where('start_time', '=', $request->fromTime);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('end_time', '=', $request->toTime);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->fromTime);
                        $qr2->where('end_time', '>', $request->fromTime);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->toTime);
                        $qr2->where('end_time', '>', $request->toTime);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '<', $request->fromTime);
                        $qr2->where('end_time', '>', $request->toTime);
                    })
                    ->orWhere(function ($qr2) use ($request) {
                        $qr2->where('start_time', '>', $request->fromTime);
                        $qr2->where('end_time', '<', $request->toTime);
                    });
                }, '<', 1);
            $wqs = $query->get();
        }else{
            $wqs = Workshop::all();
        }
        
        if (!(empty($request->sortType))) {    
            foreach ($wqs as &$eachdata) {
                $eachdata->distance = SortLocationService::theGreatCircleDistance(
                    $request->latitude,
                    $request->longitude,
                    $eachdata->latitude,
                    $eachdata->longitude);
            }
            $wqs = ($request->sortType == "nearest") ? $wqs->sortBy('distance') : $wqs->sortByDesc('distance');
        }
        $data = $wqs;
        return [
            'data' => $data->values()
        ];
    }
}
