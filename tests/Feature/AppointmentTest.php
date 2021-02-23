<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Appointment;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        \DB::beginTransaction();
        $this->contact = factory(\App\Contact::class)->create();
        $this->car = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);
        $this->workshop = factory(\App\Workshop::class)->create();
    }
    public function tearDown(): void
    {
        \DB::rollback();
        parent::tearDown();
    }
    private function getAppointmentParam($params=[])
    {
        if (isset($params['workshop_id'])) {
            if(\App\Workshop::find($params['workshop_id'])){
                $workshop_id = $params['workshop_id'];
            }else{
                $newWorkshop = factory(\App\Workshop::class)->create(['id' => $params['workshop_id']]);
                $workshop_id = $newWorkshop->id;
            }
        }
        if (isset($params['car_id'])) {
            if(\App\Car::find($params['car_id'])){
                $car_id = $params['car_id'];
            }else{
                $newCar = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);
                $car_id = $newCar->id;
            }
        }
        return [
            'workshop_id' => $workshop_id ?? $this->workshop->id,
            'car_id' => $car_id ?? $this->car->id,
            'start_time' => $params['start_time'] ?? 900,
            'end_time' => $params['end_time'] ?? 1000,
        ];
    }
    private function sampleAppointment($arr)
    {
        foreach($arr as $time){
            factory(\App\Appointment::class)->create($this->getAppointmentParam(['start_time'=> $time[0],'end_time'=>$time[1]]));
        }
    }
    public function testAppointmentGetAll()
    {
        foreach (range(1,50) as $v) {
            factory(\App\Appointment::class)->create($this->getAppointmentParam());
        }
        $response = $this->get('/api/appointments?is_disable_pagination=1');
        $response->assertStatus(200);

        $data = $response['data'];
        $this->assertEquals(count($data), 50);
    }

    public function testAppointmentGetFilterByWorkshop()
    {
        foreach (range(1,10) as $v) {
            factory(\App\Appointment::class)->create($this->getAppointmentParam(['workshop_id' => 99]));
            factory(\App\Appointment::class)->create($this->getAppointmentParam(['workshop_id' => 98]));
        }
        foreach (range(1,30) as $v) {
            factory(\App\Appointment::class)->create($this->getAppointmentParam(['workshop_id' => 97]));
        }

        // filter single
        $response = $this->get('/api/appointments?is_disable_pagination=1&workshop_ids=99');
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(count($data), 10);

        $response = $this->get('/api/appointments?is_disable_pagination=1&workshop_ids=98');
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(count($data), 10);

        $response = $this->get('/api/appointments?is_disable_pagination=1&workshop_ids=97');
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(count($data), 30);

        // filter multiple
        $response = $this->get('/api/appointments?is_disable_pagination=1&workshop_ids=97,98');
        $response->assertStatus(200);
        $data = $response['data'];
        $this->assertEquals(count($data), 40);
    }

    public function testSetNewAppointmentFail()
    {
        // occupied 9.30-10.30, 12.30-2.00, 4.00-6.00
        $this->sampleAppointment([[930,1030], [1230,1400], [1600,1800]]);

        $response = $this->post('/api/appointments', $this->getAppointmentParam(['start_time'=> 900,'end_time' => 1000]));
        $response->assertStatus(401);
        $this->assertEquals($response['error'], 'no_available_slot');
    }
    public function testSetNewAppointmentSuccess()
    {
        // occupied 9.30-10.30, 12.30-2.00, 4.00-6.00
        $this->sampleAppointment([[930,1030], [1230,1400], [1600,1800]]);

        $response = $this->post('/api/appointments', $this->getAppointmentParam(['start_time'=> 1030,'end_time' => 1100]));
        $response->assertStatus(200);
        $this->assertEquals($response['data']['workshop_id'], $this->workshop->id);
        $this->assertEquals($response['data']['car_id'], $this->car->id);
        $this->assertEquals($response['data']['start_time'], 1030);
        $this->assertEquals($response['data']['end_time'], 1100);
    }
}
