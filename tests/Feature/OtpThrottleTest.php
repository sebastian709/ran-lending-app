<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_auth_send_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/forgot-auth-send', [
                'email' => 'nobody@example.com',
            ]);
        }

        $this->post('/forgot-auth-send', [
            'email' => 'nobody@example.com',
        ])->assertStatus(429);
    }
}

