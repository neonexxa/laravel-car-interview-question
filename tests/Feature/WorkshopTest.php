<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkshopTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        \DB::beginTransaction();
        $this->contact = factory(\App\Contact::class)->create();
        $this->car1 = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);
        $this->car2 = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);
        $this->car3 = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);

        
        $workshops = [
            ['id' => 1, 'latitude' => 3.221342,'longitude' => 101.725098, 'opening_time' => 900,'closing_time' => 1800], //distance 48
            ['id' => 2, 'latitude' => 3.221272,'longitude' => 101.725040, 'opening_time' => 900,'closing_time' => 1800], //distance 38
            ['id' => 3, 'latitude' => 3.223225,'longitude' => 101.727531, 'opening_time' => 900,'closing_time' => 1200], //distance 390
            ['id' => 4, 'latitude' => 3.221868,'longitude' => 101.725852, 'opening_time' => 1100,'closing_time' => 1600], //distance 150
        ];
        foreach ($workshops as $workshop) {
            factory(\App\Workshop::class)->create($workshop);
        }
    }
    public function tearDown(): void
    {
        \DB::rollback();
        parent::tearDown();
    }
    private function getAppointmentParam($param)
    {
        return [
            'workshop_id' => $params['workshop_id'],
            'car_id' => $params['car_id'],
            'start_time' => $params['start_time'],
            'end_time' => $params['end_time'],
        ];
    }
    private function getReqAvailableWorkshop($sort,$start_time,$end_time) 
    {
        return $this->get("/api/workshops?sortType=$sort&is_available=1&start_time=$start_time&end_time=$end_time&latitude=3.221090&longitude=101.724741");
    }
    public function testWrokshopGetAll()
    {
        $response = $this->get("/api/workshops");
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEqualsCanonicalizing(array_column($data, 'id'),[1,2,3,4]);
    }
    public function testWrokshopGetAllAvailable()
    {
        
        factory(\App\Appointment::class)->create([
            'workshop_id' => 1,'car_id' => $this->car1->id,
            'start_time' => 1100,'end_time' => 1200]);
        factory(\App\Appointment::class)->create([
            'workshop_id' => 4,'car_id' => $this->car1->id,
            'start_time' => 1100,'end_time' => 1400]);
        factory(\App\Appointment::class)->create([
            'workshop_id' => 2,'car_id' => $this->car2->id,
            'start_time' => 1600,'end_time' => 1800]);
        factory(\App\Appointment::class)->create([
            'workshop_id' => 3,'car_id' => $this->car2->id,
            'start_time' => 1000,'end_time' => 1200]);
        factory(\App\Appointment::class)->create([
            'workshop_id' => 2,'car_id' => $this->car2->id,
            'start_time' => 900,'end_time' => 1000]);
        
        // opening     : w1(9-18) , w2(9-18)      , w2(9-12) , w4(11-16)
        // appointment : w1(11-12), w2(9-10,16-18), w3(10-12), w4(11-14)
        $response = $this->getReqAvailableWorkshop('nearest',900,1030);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[1]);
        $response = $this->getReqAvailableWorkshop('nearest',1000,1100);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[2,1]);
        $response = $this->getReqAvailableWorkshop('nearest',1100,1200);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[2]);
        $response = $this->getReqAvailableWorkshop('nearest',1200,1300);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[2,1]);
        $response = $this->getReqAvailableWorkshop('nearest',1400,1500);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[2,1,4]);
        $response = $this->getReqAvailableWorkshop('furthest',1400,1500);
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(array_column($data, 'id'),[4,1,2]);
    }
}
