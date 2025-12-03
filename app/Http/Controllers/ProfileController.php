<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();

        $usersInformation = DB::table('users')
            ->join('user_incomes', 'users.id', '=', 'user_incomes.user_id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'users.firstname',
                'users.lastname',
                'users.middlename',
                'users.username',
                'users.contactno',
                'users.email',
                'user_incomes.occupation',
                'user_incomes.income',
                'user_incomes.employment_status',
                'user_incomes.specified_others',
                'users.referral_source_id',
                'user_details.house_no',
                'user_details.street',
                'user_details.barangay',
                'user_details.city',
                'user_details.province',
                'users.profile_src',
            )
            ->where('users.status', 1)
            ->where('users.id', $userId)
            ->first();

        $loanApplicationCount = DB::table('loan_application')
            ->where('status', 1)
            ->where('loan_applicant', $userId)
            ->count();

        return view('borrower.pages.profile', [
            'usersInformation' => $usersInformation,
            'loanApplication' => $loanApplicationCount
        ]);
    }


    public function adminIndex()
    {

        $userId = auth()->id();
        $usersInformation = DB::table('users')
            ->join('user_incomes', 'users.id', '=', 'user_incomes.user_id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'users.firstname',
                'users.lastname',
                'users.middlename',
                'users.username',
                'users.contactno',
                'users.email',
                'user_incomes.occupation',
                'user_incomes.income',
                'user_incomes.employment_status',
                'users.referral_source_id',
                'user_details.house_no',
                'user_details.street',
                'user_details.barangay',
                'user_details.city',
                'user_details.province',
                'users.profile_src',
            )
            ->where('users.status', 1)
            ->where('users.id', $userId)
            ->first();


        return view('admin.pages.profile.index', compact('usersInformation'));
    }

    public function update(Request $request)
    {
        $userId = auth()->id();

        try {
            // Start transaction
            DB::beginTransaction();

            // Update users table
            DB::table('users')->where('id', $userId)->update([
                'username' => $request->username,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'lastname' => $request->last_name,
                'referral_source_id' => $request->referral_source_id,
                'updated_at' => now()
            ]);

            // Update user_details table
            DB::table('user_details')->where('user_id', $userId)->update([
                'house_no' => $request->house_no,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'updated_at' => now()
            ]);
            $income = preg_replace('/[^\d.]/', '', $request->input('income'));

            // Update user_incomes table
            DB::table('user_incomes')->where('user_id', $userId)->update([
                'occupation' => $request->occupation,
                'income' => $income,
                'employment_status' => $request->employment_status,
                'specified_others' => $request->specify_others,
                'updated_at' => now()
            ]);

            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Create folder if not exists
                if (!Storage::disk('public')->exists('upload/profile_picture')) {
                    Storage::disk('public')->makeDirectory('upload/profile_picture');
                }

                // Save the file
                $path = $file->storeAs('upload/profile_picture', $filename, 'public');

                // Save path in DB
                DB::table('users')->where('id', $userId)->update([
                    'profile_src' => $path,
                    'updated_at' => now()
                ]);
            }



            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function adminUpdate(Request $request)
    {
        $userId = auth()->id();

        try {
            // Start transaction
            DB::beginTransaction();

            // Update users table
            DB::table('users')->where('id', $userId)->update([
                'username' => $request->username,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'lastname' => $request->last_name,
                'updated_at' => now()
            ]);

            // Update user_details table
            DB::table('user_details')->where('user_id', $userId)->update([
                'house_no' => $request->house_no,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'updated_at' => now()
            ]);


            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Create folder if not exists
                if (!Storage::disk('public')->exists('upload/profile_picture')) {
                    Storage::disk('public')->makeDirectory('upload/profile_picture');
                }

                // Save the file
                $path = $file->storeAs('upload/profile_picture', $filename, 'public');

                // Save path in DB
                DB::table('users')->where('id', $userId)->update([
                    'profile_src' => $path,
                    'updated_at' => now()
                ]);
            }



            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword()
    {
        return view('borrower.pages.change-password');
    }

    public function adminChangePassword()
    {
        return view('admin.pages.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.'
            ], 422); // HTTP 422: Unprocessable Entity
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Your password has been successfully changed.',
            'logout' => true
        ]);
    }

    public function adminUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.'
            ], 422); // HTTP 422: Unprocessable Entity
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Your password has been successfully changed.',
            'logout' => true
        ]);
    }

    public function loanList()
    {
        // $loans = collect([
        //     (object) [
        //         'id' => 'LN-0001',
        //         'applied_at' => '2025-07-20',
        //         'amount' => 50000,
        //         'status' => 'For Interview',
        //         'approved_at' => '2025-07-21',
        //         'disbursed_at' => '2025-07-22',
        //         'closed_at' => null,
        //         'remarks' => 'Awaiting documents.'
        //     ]
        // ]);

        $userId = auth()->id();

        $loans = DB::table('loan_application as la')
            ->join('loan_status as ls', 'ls.id', '=', 'la.loan_status')
            ->select(
                'la.id',
                'la.created_at as applied_at',
                'la.loan_amount as amount',
                'ls.loan_status AS status',
                DB::raw('(
                    SELECT MAX(al.created_at)
                    FROM activity_logs al
                    WHERE al.loan_id = la.id
                    AND (
                        SELECT COUNT(DISTINCT al2.user_id)
                        FROM activity_logs al2
                        WHERE al2.loan_id = la.id
                    ) = 3
                ) as approved_at'),
                DB::raw('(
                    SELECT created_at
                    FROM admin_money_transfer amt
                    WHERE amt.loan_id = la.id
                ) as disbursed_at'),
                DB::raw('null as closed_at'), // ??? san kukunin tu
                DB::raw("null as remarks") // ??? san kukunin tu
            )
            ->where('la.status', 1)
            ->where('la.loan_applicant', $userId)
            ->orderBy("la.id","DESC")
            ->get();

        return view('borrower.pages.loan-list', compact('loans'));
    }

    public function loanListViewDetails(Request $request)
    {
        $loan_id = $request->loan_id;
        $userId = auth()->id();

        // 🔹 Primary Loan
        $primaryLoan = DB::table('loan_application as la')
            ->select(
                'la.id',
                'la.loan_tenure',
                'la.loan_amount',
                DB::raw("CONCAT(la.loan_tenure, ' months') as loan_tenure_label")
            )
            ->where('la.id', $loan_id)
            ->first();

        // 🔹 Breakdown Loan
        $breakDownLoan = DB::table('loan_tenure as lt')
            ->join('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
            ->select(
                'lt.date',
                DB::raw('(COALESCE(lt.principal, 0) + COALESCE(lti.interest, 0)) as monthly_amount_due'),
                DB::raw('COALESCE(lt.principal, 0) as principal'),
                DB::raw('COALESCE(lti.interest, 0) as interest'),
                DB::raw('COALESCE(ltp.penalty, 0) as penalty')
            )
            ->where('lt.loan_id', $loan_id)
            ->get();

        // 🔹 Total Penalty
        $totalPenalty = $breakDownLoan->sum('penalty');
        $totalAmount = $breakDownLoan->sum( 'monthly_amount_due');

        // 🔹 Final Output
        $final_output = [
            "loanID" => $primaryLoan->id ?? null,
            "loanTenure" => $primaryLoan->loan_tenure ?? null,
            "totalAmount" => $totalAmount ?? 0,
            "totalPenalty" => $totalPenalty ?? 0,
            "breakDownLoan" => $breakDownLoan
        ];

        return response()->json($final_output);
    }


}
