<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_halaman_login_dapat_dibuka(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
