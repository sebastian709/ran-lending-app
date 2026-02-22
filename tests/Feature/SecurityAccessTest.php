<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected_from_sensitive_endpoints(): void
    {
        $this->get('/paymentpage')->assertRedirect('/login');
        $this->post('/executive/add_investment', ['amount' => 1000, 'remarks' => 'test'])->assertRedirect('/login');
        $this->get('/admin/get-total-applications')->assertRedirect('/login');
        $this->get('/admin/loan-request')->assertRedirect('/login');
    }

    public function test_authenticated_non_admin_users_receive_forbidden_for_admin_only_controllers(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/paymentpage')->assertForbidden();
        $this->post('/executive/add_investment', ['amount' => 1000, 'remarks' => 'test'])->assertForbidden();
        $this->get('/admin/get-total-applications')->assertForbidden();
        $this->get('/admin/loan-request')->assertForbidden();
    }

    public function test_authenticated_admin_can_access_admin_dashboard_api(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->get('/admin/get-total-applications')->assertOk();
    }
}
