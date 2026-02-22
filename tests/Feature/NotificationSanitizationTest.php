<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationSanitizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_message_is_sanitized_and_icon_is_whitelisted(): void
    {
        $sender = User::factory()->create();
        $target = User::factory()->create();

        $this->mock(FirebaseService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')->once()->andReturnTrue();
        });

        $this->actingAs($sender)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 2,
                'user_id' => $target->id,
                'icon' => '<img src=x onerror=alert(1)>',
                'message' => '<p>Hello</p><script>alert(1)</script><b>World</b>',
                'data_url' => '/home',
            ])
            ->assertOk();

        $record = DB::table('notification_data')
            ->where('user_id', $target->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($record);
        $this->assertSame('', $record->icon);
        $this->assertStringNotContainsString('<script>', $record->message);
        $this->assertStringContainsString('<b>World</b>', $record->message);
    }

    public function test_notification_data_url_rejects_non_relative_urls(): void
    {
        $sender = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($sender)
            ->post('/send-notification', [
                'table_id' => 'notifications',
                'target_type' => 2,
                'user_id' => $target->id,
                'icon' => '<i class="ri-notification-line"></i>',
                'message' => 'Hello',
                'data_url' => 'javascript:alert(1)',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('data_url');
    }
}

