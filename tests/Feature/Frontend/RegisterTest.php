<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_halaman_register_dapat_dibuka(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
}
