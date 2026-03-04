<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BorrowerLoanSingleApplicationGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_precheck_blocks_when_user_has_non_draft_active_loan(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        DB::table('loan_application')->insert([
            'loan_applicant' => $user->id,
            'loan_status' => 1, // pending / in-progress
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/borrower/save-precheck', [
            'load_step' => 1,
            'purpose_of_loan' => 'Business capital',
            'referral' => 'friend',
            'occupation' => 'Developer',
            'income' => '25000',
            'employmentStatus' => 1,
            'loan_type' => 'Express',
            'scheduled_date' => null,
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'You already have an active loan application.',
            ]);

        $this->assertSame(1, DB::table('loan_application')->where('loan_applicant', $user->id)->count());
    }

    public function test_save_precheck_reuses_existing_draft_loan(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $draftLoanId = DB::table('loan_application')->insertGetId([
            'loan_applicant' => $user->id,
            'loan_status' => 0, // draft
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/borrower/save-precheck', [
            'load_step' => 1,
            'purpose_of_loan' => 'Emergency',
            'referral' => 'others',
            'occupation' => 'Freelancer',
            'income' => '18000',
            'employmentStatus' => 1,
            'loan_type' => 'Scheduled',
            'scheduled_date' => now()->addDays(5)->toDateString(),
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'loan_application_id' => $draftLoanId,
            ]);

        $this->assertSame(1, DB::table('loan_application')->where('loan_applicant', $user->id)->count());
    }

    public function test_update_loan_details_create_path_blocks_when_active_loan_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        DB::table('loan_application')->insert([
            'loan_applicant' => $user->id,
            'loan_status' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/borrower/update-loan-details', [
            'load_step' => 2,
            'loan_amount' => '10000',
            'loan_tenure' => 3,
            'interest_rate' => '0.050',
            'total_amount' => '10500',
            // no loan_application_id -> create path
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'You already have an active loan application.',
            ]);

        $this->assertSame(1, DB::table('loan_application')->where('loan_applicant', $user->id)->count());
    }
}

