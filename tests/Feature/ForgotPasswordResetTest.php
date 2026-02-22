<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_requires_a_valid_unexpired_otp(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass#123'),
        ]);

        DB::table('password_otps')->insert([
            'email' => $user->email,
            'otp' => '123456',
            'expires_at' => now()->subMinute(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/forgot-auth-changepass', [
            'email' => $user->email,
            'otp' => '123456',
            'pass' => 'NewPass#123',
        ]);

        $response->assertOk()->assertContent('0');

        $user->refresh();
        $this->assertTrue(Hash::check('OldPass#123', $user->password));
    }

    public function test_password_reset_updates_password_when_otp_is_valid(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass#123'),
        ]);

        DB::table('password_otps')->insert([
            'email' => $user->email,
            'otp' => '654321',
            'expires_at' => now()->addMinutes(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/forgot-auth-changepass', [
            'email' => $user->email,
            'otp' => '654321',
            'pass' => 'NewPass#123',
        ]);

        $response->assertOk()->assertContent('1');

        $user->refresh();
        $this->assertTrue(Hash::check('NewPass#123', $user->password));
        $this->assertDatabaseMissing('password_otps', ['email' => $user->email]);
    }
}
