<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationValidationAndThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_notification_validates_target_specific_fields(): void
    {
        $user = User::factory()->create();

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->never();
        });

        $this->actingAs($user)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 2,
                'icon' => 'bell',
                'message' => 'Missing user id',
                'data_url' => '/home',
            ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'user_id is required for target_type 2',
            ]);
    }

    public function test_send_notification_is_rate_limited_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->zeroOrMoreTimes()->andReturnTrue();
        });

        for ($i = 0; $i < 20; $i++) {
            $this->actingAs($user)
                ->post('/send-notification', [
                    'table_id' => 'notifications',
                    'target_type' => 2,
                    'user_id' => $user->id,
                    'icon' => 'bell',
                    'message' => 'Ping',
                    'data_url' => '/home',
                ]);
        }

        $this->actingAs($user)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 2,
                'user_id' => $user->id,
                'icon' => 'bell',
                'message' => 'Ping',
                'data_url' => '/home',
            ])
            ->assertStatus(429);
    }
}

