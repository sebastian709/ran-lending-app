<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationPaginationBoundsTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_data_endpoint_clamps_limit_and_offset(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 60; $i++) {
            DB::table('notification_data')->insert([
                'user_id' => $user->id,
                'is_read' => 0,
                'icon' => '<i class="ri-notification-line"></i>',
                'message' => 'Notice ' . $i,
                'data_url' => '/home',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this->actingAs($user)
            ->get('/get-notification-data?limit=1000&offset=-50');

        $response->assertOk();
        $response->assertJsonPath('limit', 50);
        $response->assertJsonPath('offset', 0);

        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertCount(50, $data);
    }
}

