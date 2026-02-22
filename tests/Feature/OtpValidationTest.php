<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_auth_check_requires_existing_email(): void
    {
        $this->post('/forgot-auth-check', [
            'email' => 'missing@example.com',
            'otp' => '123456',
        ])->assertStatus(302)->assertSessionHasErrors('email');
    }

    public function test_get_attachment_requires_numeric_payment_id(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)
            ->post('/payment/get-attachment', ['payment_id' => 'abc'])
            ->assertStatus(302)
            ->assertSessionHasErrors('payment_id');
    }
}

