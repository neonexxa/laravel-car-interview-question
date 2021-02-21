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
        $query = Workshop::where('workshops.opening_time', '<=', $request->fromTime )
            ->where('workshops.closing_time', '>=', $request->toTime );
        if (!(empty($request->is_available))) {
            $query->whereHas('appointments', function($qr) use($request){
                $qr->where(function ($qr2) use ($request) {
                    $qr2->where('start_time', '>', $request->fromTime);
                    $qr2->where('end_time', '<', $request->toTime);
                })
                ->orWhere(function ($qr2) use ($request) {
                    $qr2->where('start_time', '<', $request->fromTime);
                    $qr2->where('end_time', '>', $request->toTime);
                })
                ->orWhere(function ($qr2) use ($request) {
                    $qr2->where('start_time', '<', $request->fromTime);
                    $qr2->where('end_time', '<', $request->toTime);
                })
                ->orWhere(function ($qr2) use ($request) {
                    $qr2->where('start_time', '>', $request->fromTime);
                    $qr2->where('end_time', '>', $request->toTime);
                });
            });
        }
        $wqs = $query->get();
        foreach ($wqs as &$eachdata) {
            $eachdata->distance = SortLocationService::theGreatCircleDistance(
                $request->latitude,
                $request->longitude,
                $eachdata->latitude,
                $eachdata->longitude);
        }
        $data = $wqs->sortBy('distance');
        return [
            'data' => $data->values()
        ];
    }
}
