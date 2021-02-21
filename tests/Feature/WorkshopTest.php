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
        $this->car = factory(\App\Car::class)->create(['contact_id' => $this->contact->id]);
    }
    public function tearDown(): void
    {
        \DB::rollback();
        parent::tearDown();
    }
    public function testWrokshopGetAll()
    {
        foreach (range(1,50) as $v) {
            factory(\App\Workshop::class)->create();
        }
        $response = $this->get('/api/workshops?is_disable_pagination=1');
        // $response->assertStatus(200);

        // $data = $response['data'];
        // $this->assertEquals(count($data), 50);
    }
}
