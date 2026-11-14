<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_jadwal_dapat_dibuka(): void
    {
        $response = $this->get('/schedules');

        $response->assertStatus(200);
    }
}
