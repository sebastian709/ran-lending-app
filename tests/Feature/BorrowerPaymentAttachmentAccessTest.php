<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BorrowerPaymentAttachmentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_attachment_endpoint_requires_authentication(): void
    {
        $this->post('/payment/get-attachment', ['payment_id' => 1])
            ->assertRedirect('/login');
    }

    public function test_borrower_cannot_fetch_another_users_attachment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $loanId = DB::table('loan_application')->insertGetId([
            'loan_applicant' => $owner->id,
            'loan_status' => 5,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $paymentId = DB::table('loan_payments')->insertGetId([
            'loan_application_id' => $loanId,
            'amount_sent' => 1000,
            'payment_status_id' => 1,
            'payment_type_id' => 4,
            'reference_code' => 'REF-ATTACH-001',
            'sent_to' => '1',
            'remarks' => null,
            'attachment' => 'uploads/payments/owner-proof.jpg',
            'added_by' => $owner->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($other)
            ->post('/payment/get-attachment', ['payment_id' => $paymentId])
            ->assertOk()
            ->assertJson(['attachment' => null]);

        $this->actingAs($owner)
            ->post('/payment/get-attachment', ['payment_id' => $paymentId])
            ->assertOk()
            ->assertJson(['attachment' => 'uploads/payments/owner-proof.jpg']);
    }
}

