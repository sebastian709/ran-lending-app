<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_send_requires_authentication(): void
    {
        $this->post('/send-notification', [
            'target_type' => 2,
            'user_id' => 1,
            'table_id' => 'notifications',
            'icon' => 'bell',
            'message' => 'Hello',
            'data_url' => '/home',
        ])->assertRedirect('/login');
    }

    public function test_notification_send_is_allowed_for_authenticated_non_admin(): void
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->once()->andReturnTrue();
        });

        $this->actingAs($user)
            ->post('/send-notification', [
                'target_type' => 2,
                'user_id' => $user->id,
                'table_id' => 'notifications',
                'icon' => 'bell',
                'message' => 'Hello',
                'data_url' => '/home',
            ])
            ->assertOk()
            ->assertJson(['status' => 'Notification sent!']);

        $this->assertDatabaseHas('notification_data', [
            'user_id' => $user->id,
            'message' => 'Hello',
            'data_url' => '/home',
        ]);
    }
}
