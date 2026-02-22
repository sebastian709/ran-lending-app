<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteHardeningAndThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_routes_require_authentication(): void
    {
        $this->get('/chat')->assertRedirect('/login');
        $this->get('/test-broadcast')->assertRedirect('/login');
        $this->post('/test-broadcast')->assertRedirect('/login');
    }

    public function test_debug_routes_are_forbidden_for_non_admin_users(): void
    {
        $user = User::factory()->create(['is_admin' => 0]);
        $this->actingAs($user);

        $this->get('/chat')->assertForbidden();
        $this->get('/test-broadcast')->assertForbidden();
        $this->post('/test-broadcast', [
            'username' => 'x',
            'message' => 'y',
        ])->assertForbidden();
    }

    public function test_forgot_change_password_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 6; $i++) {
            $this->post('/forgot-auth-changepass', [
                'email' => $user->email,
                'otp' => '000000',
                'pass' => 'ValidPass#1',
            ]);
        }

        $this->post('/forgot-auth-changepass', [
            'email' => $user->email,
            'otp' => '000000',
            'pass' => 'ValidPass#1',
        ])->assertStatus(429);
    }

    public function test_register_auth_check_is_rate_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/register-auth-check', [
                'email' => 'anyone@example.com',
                'otp' => '123456',
            ]);
        }

        $this->post('/register-auth-check', [
            'email' => 'anyone@example.com',
            'otp' => '123456',
        ])->assertStatus(429);
    }
}
