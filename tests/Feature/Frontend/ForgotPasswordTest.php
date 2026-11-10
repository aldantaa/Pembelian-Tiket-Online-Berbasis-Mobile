<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_halaman_forgot_password_dapat_dibuka(): void
    {
        $response = $this->get('/forgotpassword');

        $response->assertStatus(200);
    }
}
