<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ForgotPasswordEmailValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_email_route_rejects_unknown_email_and_does_not_create_otp(): void
    {
        $response = $this->post('/password/email', [
            'email' => 'unknown@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        $this->assertDatabaseCount('password_otps', 0);
    }
}

