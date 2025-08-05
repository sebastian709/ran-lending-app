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
}
