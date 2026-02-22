<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationApiOutputSanitizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_api_returns_sanitized_icon_message_and_data_url(): void
    {
        $user = User::factory()->create();

        DB::table('notification_data')->insert([
            'user_id' => $user->id,
            'is_read' => 0,
            'icon' => '<img src=x onerror=alert(1)>',
            'message' => '<p>Hello</p><script>alert(1)</script><b>World</b>',
            'data_url' => 'javascript:alert(1)',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/get-notification-data?limit=10&offset=0');

        $response->assertOk();
        $data = $response->json('data');

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame('', $data[0]['icon']);
        $this->assertSame('Helloalert(1)World', $data[0]['message']);
        $this->assertSame('', $data[0]['data_url']);
    }
}

