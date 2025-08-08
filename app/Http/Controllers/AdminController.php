<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.pages.main.index');
    }

    public function blankTesting()
    {
        return view('admin.testing-only.blankpage');
    }

    public function settings()
    {
        $loan_interest = DB::table('admin_settings')
            ->where('admin_settings.status', 1)
            ->where('admin_settings.title', "loan_interest")
            ->first();


        $result = array(
            "loan_interest" => $loan_interest->value ?? 0
        );

        return view('admin.pages.settings.index', compact('result'));
    }

    public function updateLoanSettings(Request $request)
    {
        $request->validate([
            'loan_interest' => 'required|numeric|min:0',
        ]);

        DB::table('admin_settings')
            ->where('title', 'loan_interest')
            ->update([
                'value' => $request->loan_interest,
                'updated_at' => now()
            ]);

        return response()->json(['success' => true, 'message' => 'Loan interest setting updated successfully.']);
    }


    public function viewLoanRequest()
    {
        // $loanApplication = DB::table('loan_application')
        //     ->get();


        // $result = array(
        //     "loan_interest" => $loanApplication
        // );

        // , compact('result')

        return view('admin.pages.loanrequest.index');
    }

    public function getLoanRequests()
    {
        $loanApplication = DB::table('loan_application')
            ->join('users', 'loan_application.loan_applicant', '=', 'users.id')
            ->join('loan_status', 'loan_application.loan_status', '=', 'loan_status.id')
            ->select(
                'loan_application.id',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as loan_applicant"),
                'loan_application.loan_amount',
                'loan_application.loan_tenure',
                'loan_application.interest_rate',
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                'loan_application.referral',
                'loan_application.loan_status as loan_status',
                'loan_status.loan_status as loan_status_name'
            )
            ->get();

        return response()->json($loanApplication);
    }

    public function getBorrowersApplication(Request $request){
        $loan_id = $request->loan_id;

        $loanApplication = DB::table('loan_application')
            ->join('users', 'loan_application.loan_applicant', '=', 'users.id')
            ->join('loan_status', 'loan_application.loan_status', '=', 'loan_status.id')
            ->select(
                'loan_application.id',
                'users.id as user_id',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as loan_applicant"),
                'loan_application.loan_amount',
                'loan_application.loan_tenure',
                'loan_application.interest_rate',
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                DB::raw("DATE_FORMAT(loan_application.updated_at, '%b %d, %Y') as updated_at"),
                'loan_application.referral',
                'loan_application.loan_status as loan_status',
                'loan_status.loan_status as loan_status_name',
                'loan_application.purpose_of_loan'
            )
            ->where('loan_application.id', $loan_id)
            ->first();

        return response()->json($loanApplication);
    }
}
