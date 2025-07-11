<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('borrower.pages.home');
    }

    public function fetchIncome($id)
    {
        try {
            // Fetch income info
            $income = DB::table('user_incomes')
                ->where('user_id', $id)
                ->where('status', 1)
                ->first();

            if (!$income) {
                return response()->json([
                    'message' => 'No income record found.'
                ], 404);
            }

            // Fetch loan application if exists
            $loan = DB::table('loan_application')
                ->where('loan_applicant', $id)
                ->where('loan_status', 0)
                ->first();

            return response()->json([
                'loan_applicantion_id' => $loan->id ?? '',
                'occupation' => $income->occupation ?? '',
                'income' => $income->income ?? '',
                'employment_status' => $income->employment_status ?? '',
                'purpose_of_loan' => $loan->purpose_of_loan ?? '',
                'referral' => $loan->referral ?? '',
                'loan_amount' => $loan->loan_amount ?? '',
                'loan_tenure' => $loan->loan_tenure ?? '',
                'interest_rate' => $loan->interest_rate ?? '',
                'total_amount' => $loan->total_amount ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function savePrecheck(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'load_step' => 'required|integer',
            'purpose_of_loan' => 'nullable|string',
            'referral' => 'nullable|string',
        ]);

        $existing = DB::table('loan_application')
            ->where('loan_applicant', $validated['user_id'])
            ->where('loan_status', 0)
            ->first();

        if ($existing) {
            // Update existing
            DB::table('loan_application')
                ->where('id', $existing->id)
                ->update([
                    'purpose_of_loan' => $validated['purpose_of_loan'],
                    'referral' => $validated['referral'],
                    'load_step' => $validated['load_step'],
                    'updated_at' => now()
                ]);

            $loanId = $existing->id; // ← get the existing ID
        } else {
            // Insert new and get the ID
            $loanId = DB::table('loan_application')->insertGetId([
                'load_step' => $validated['load_step'],
                'loan_applicant' => $validated['user_id'],
                'purpose_of_loan' => $validated['purpose_of_loan'],
                'referral' => $validated['referral'],
                'loan_status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'loan_applicantion_id' => $loanId
        ]);
    }

    public function updateLoanDetails(Request $request)
    {
        $validated = $request->validate([
            'loan_application_id' => 'required|integer',
            'load_step' => 'required|integer',
            'loan_amount' => 'required|numeric',
            'loan_tenure' => 'required|integer',
            'interest_rate' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        // Update the loan_application record
        $updated = DB::table('loan_application')
            ->where('id', $validated['loan_application_id'])
            ->update([
                'load_step' => $validated['load_step'],
                'loan_amount' => $validated['loan_amount'],
                'loan_tenure' => $validated['loan_tenure'],
                'interest_rate' => $validated['interest_rate'],
                'total_amount' => $validated['total_amount'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => $updated > 0,
            'message' => $updated ? 'Loan details updated successfully.' : 'No changes made.'
        ]);
    }

}
