<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SortLocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DistanceTest extends TestCase
{
    // all with assumption of direct distance non road, ref google map, vary with earth coefficient
    public function testGetDistance()
    {
        $latitude_origin = 3.221090;
        $longitude_origin = 101.724741;
        
        $location_output_30m_from_origin = SortLocationService::theGreatCircleDistance($latitude_origin,$longitude_origin, 3.221272,101.725040);
        $location_output_50m_from_origin = SortLocationService::theGreatCircleDistance($latitude_origin,$longitude_origin, 3.221342,101.725098);
        $this->assertTrue($location_output_30m_from_origin > 20 && $location_output_30m_from_origin < 40);
        $this->assertTrue($location_output_50m_from_origin > 40 && $location_output_50m_from_origin < 60);

        $location_output_150m_from_origin = SortLocationService::theGreatCircleDistance($latitude_origin,$longitude_origin, 3.221868,101.725852);
        $location_output_450m_from_origin = SortLocationService::theGreatCircleDistance($latitude_origin,$longitude_origin, 3.223225,101.727531);
        $this->assertTrue($location_output_150m_from_origin > 100 && $location_output_150m_from_origin < 200);
        $this->assertTrue($location_output_450m_from_origin > 350 && $location_output_450m_from_origin < 450);

    }

    public function testGetDistanceBulk()
    {
        $latitude_origin = 3.221090;
        $longitude_origin = 101.724741;

        $workshops = [
            ['id'=> 1,'latitude' => 3.221342,'longitude' => 101.725098], //48
            ['id'=> 2,'latitude' => 3.221272,'longitude' => 101.725040], //38
            ['id'=> 3,'latitude' => 3.223225,'longitude' => 101.727531], //390
            ['id'=> 4,'latitude' => 3.221868,'longitude' => 101.725852], //150
        ];
        $sortedWorkshops = SortLocationService::getAllDistanceNearest($latitude_origin,$longitude_origin, $workshops);
        $this->assertEqualsCanonicalizing(array_column($sortedWorkshops, 'id'),[2,1,4,3]);
    }
}
