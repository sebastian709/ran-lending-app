<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        $loanStatus = $this->getLoanStatus();

        return view('borrower.pages.home', compact('loanStatus'));
    }

    public function fetchIncome($id)
    {
        try {
            // Fetch income info
            $income = DB::table('user_incomes')
                ->join('users', 'user_incomes.user_id', '=', 'users.id')
                ->select(
                    'user_incomes.occupation',
                    'user_incomes.income',
                    'user_incomes.employment_status',
                    DB::raw('CONCAT(users.firstname, " ", users.lastname) as fullname')
                )
                ->where('user_incomes.user_id', $id)
                ->where('user_incomes.status', 1)
                ->first();

            if (!$income) {
                return response()->json([
                    'message' => 'No income record found.'
                ], 404);
            }

            // Fetch loan application if exists
            $loan = DB::table('loan_application')
                ->where('loan_applicant', $id)
                ->where('status', 1)
                ->where('loan_status', 0)
                ->first();

            return response()->json([
                'loan_application_id' => $loan->id ?? '',
                'fullname' => $income->fullname ?? '',
                'occupation' => $income->occupation ?? '',
                'income' => $income->income ?? '',
                'employment_status' => $income->employment_status ?? '',
                'purpose_of_loan' => $loan->purpose_of_loan ?? '',
                'referral' => $loan->referral ?? '',
                'loan_amount' => $loan->loan_amount ?? '',
                'loan_tenure' => $loan->loan_tenure ?? '',
                'interest_rate' => $loan->interest_rate ?? '',
                'total_amount' => $loan->total_amount ?? '',

                // Additional Fields
                'payslip_img' => $loan->payslip_img ?? '',
                'bank_name' => $loan->bank_name ?? '',
                'account_number' => $loan->account_number ?? '',
                'upload_qr_code_img' => $loan->upload_qr_code_img ?? '',
                'government_type_id' => $loan->government_type_id ?? '',
                'government_id_img' => $loan->government_id_img ?? '',
                'billing_statement_img' => $loan->billing_statement_img ?? '',
                'signature_img' => $loan->signature_img ?? '',
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
        $userId = auth()->id();

        $validated = $request->validate([
            'load_step' => 'required|integer',
            'purpose_of_loan' => 'nullable|string',
            'referral' => 'nullable|string',
            'occupation' => 'required|string',
            'income' => 'required|numeric',
            'employmentStatus' => 'required|integer',
        ]);

        $existing = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->where('loan_status', 0)
            ->first();

        if ($existing) {
            // Update user income
            DB::table('user_incomes')
                ->where('user_id', $userId)
                ->update([
                    'occupation' => $validated['occupation'],
                    'income' => $validated['income'],
                    'employment_status' => $validated['employmentStatus'],
                    'updated_at' => now()
                ]);

            // Update existing loan
            DB::table('loan_application')
                ->where('id', $existing->id)
                ->update([
                    'purpose_of_loan' => $validated['purpose_of_loan'],
                    'referral' => $validated['referral'],
                    'load_step' => $validated['load_step'],
                    'updated_at' => now()
                ]);

            

            $loanId = $existing->id;
        } else {
            // Update or insert income record
            DB::table('user_incomes')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'occupation' => $validated['occupation'],
                        'income' => $validated['income'],
                        'employment_status' => $validated['employmentStatus'],
                        'updated_at' => now()
                    ]
                );
            // Insert new loan
            $loanId = DB::table('loan_application')->insertGetId([
                'load_step' => $validated['load_step'],
                'loan_applicant' => $userId,
                'purpose_of_loan' => $validated['purpose_of_loan'],
                'referral' => $validated['referral'],
                'loan_status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            
        }

        return response()->json([
            'success' => true,
            'loan_application_id' => $loanId,
            'referral_type' => $validated['referral']
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

    public function finalSubmit(Request $request)
    {
        try {
            $request->validate([
                'loan_application_id' => 'required|integer',
                'load_step' => 'required|integer',
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'government_type_id' => 'required|integer',
                'payslip_img' => 'required|file|mimes:jpg,jpeg,png,pdf',
                'qr_code_img' => 'required|file|mimes:jpg,jpeg,png',
                'government_id_img' => 'required|file|mimes:jpg,jpeg,png',
                'billing_statement_img' => 'required|file|mimes:jpg,jpeg,png,pdf',
                'signature_img' => 'required|string',
            ]);

            $data = [
                'load_step' => $request->load_step,
                'loan_status' => 2, // Pending
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'government_type_id' => $request->government_type_id,
                'updated_at' => now(),
            ];

            // Map the request field to the database column
            $folderMap = [
                'payslip_img' => ['folder' => 'payslip', 'db_field' => 'payslip_img'],
                'qr_code_img' => ['folder' => 'qr_code', 'db_field' => 'upload_qr_code_img'],
                'government_id_img' => ['folder' => 'government_id', 'db_field' => 'government_id_img'],
                'billing_statement_img' => ['folder' => 'billing_statement', 'db_field' => 'billing_statement_img'],
            ];

            foreach ($folderMap as $requestField => $info) {
                if ($request->hasFile($requestField)) {
                    $file = $request->file($requestField);
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("storage/uploads/{$info['folder']}"), $filename);
                    $data[$info['db_field']] = "uploads/{$info['folder']}/{$filename}";
                }
            }

            // Handle base64 signature
            if ($request->filled('signature_img')) {
                $base64 = $request->input('signature_img');
                if (Str::startsWith($base64, 'data:image')) {
                    $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
                    $ext = 'png';
                    $filename = uniqid() . '.' . $ext;

                    $directory = public_path("storage/uploads/signature");
                    if (!file_exists($directory)) {
                        mkdir($directory, 0775, true);
                    }

                    $path = "{$directory}/{$filename}";
                    file_put_contents($path, $image);
                    $data['signature_img'] = "uploads/signature/{$filename}";
                }
            }

            DB::table('loan_application')
                ->where('id', $request->loan_application_id)
                ->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Final application submitted successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Final Submit Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLoanStatus()
    {
        $userId = auth()->id();

        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        return $loanApplication->loan_status ?? 999;
    }

}

