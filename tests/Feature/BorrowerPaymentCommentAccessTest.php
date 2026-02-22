<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BorrowerPaymentCommentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_borrower_cannot_access_other_users_payment_comments(): void
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
            'amount_sent' => 1200,
            'payment_status_id' => 1,
            'payment_type_id' => 4,
            'reference_code' => 'REF-COMMENT-001',
            'sent_to' => '1',
            'remarks' => null,
            'attachment' => 'uploads/payments/proof.jpg',
            'added_by' => $owner->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tenureId = DB::table('loan_tenure')->insertGetId([
            'loan_id' => $loanId,
            'date' => now()->toDateString(),
            'principal' => 1000,
            'payment_status_id' => 1,
            'count' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('loan_payment_comments')->insert([
            'payment_id' => $paymentId,
            'tenure_id' => $tenureId,
            'comment' => 'Owner comment',
            'comment_by' => $owner->id,
            'status' => 1,
            'created_at' => now(),
        ]);

        $this->actingAs($other)
            ->get('/payment-comments?payment_id=' . $paymentId . '&tenure_id=' . $tenureId)
            ->assertForbidden();

        $this->actingAs($other)
            ->post('/payment/comment', [
                'payment_id' => $paymentId,
                'tenure_id' => $tenureId,
                'comment' => 'Intruder comment',
            ])
            ->assertForbidden();

        $this->actingAs($owner)
            ->get('/payment-comments?payment_id=' . $paymentId . '&tenure_id=' . $tenureId)
            ->assertOk();
    }
}

