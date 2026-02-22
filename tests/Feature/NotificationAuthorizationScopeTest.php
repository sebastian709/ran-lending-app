<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationAuthorizationScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_broadcast_notifications_to_role_groups(): void
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->never();
        });

        $this->actingAs($user)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 1,
                'level_id' => 2,
                'icon' => '<i class="ri-notification-line"></i>',
                'message' => 'Broadcast attempt',
                'data_url' => '/home',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_broadcast_notifications_to_role_groups(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        User::factory()->create(['is_admin' => 0]);
        User::factory()->create(['is_admin' => 0]);

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->once()->andReturnTrue();
        });

        $this->actingAs($admin)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 1,
                'level_id' => 2,
                'icon' => '<i class="ri-notification-line"></i>',
                'message' => 'Admin broadcast',
                'data_url' => '/home',
            ])
            ->assertOk();

        $nonAdminCount = DB::table('users')->where('is_admin', 0)->count();
        $savedCount = DB::table('notification_data')->where('message', 'Admin broadcast')->count();
        $this->assertSame($nonAdminCount, $savedCount);
    }

    public function test_non_admin_group_send_is_limited_to_twenty_recipients(): void
    {
        $sender = User::factory()->create(['is_admin' => 0]);
        $targets = User::factory()->count(21)->create(['is_admin' => 0]);

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->never();
        });

        $this->actingAs($sender)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 3,
                'group_user_id' => $targets->pluck('id')->toArray(),
                'icon' => '<i class="ri-notification-line"></i>',
                'message' => 'Group send',
                'data_url' => '/home',
            ])
            ->assertForbidden();
    }
}
