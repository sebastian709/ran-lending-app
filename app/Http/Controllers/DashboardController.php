<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;


class DashboardController extends Controller
{

    public function index()
    {
          return view('admin.pages.main.index')->render();
    }

   public function getTotalApplications(Request $request)
    {
        $statuses = [
            1 => 'pending',
            2 => 'for_interview',
            3 => 'for_revision',
            4 => 'waiting',
            5 => 'transferred',
            6 => 'rejected',
            7 => 'closed',
            8 => 'scheduled',
            9 => 'cancelled'
        ];

        $totals = [];

    
        $filter = $request->input('filter', 'month'); // default
        $query = DB::table('loan_application');

        if ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        foreach ($statuses as $key => $label) {
            $totals[$label] = (clone $query)->where('loan_status', $key)->count('id');
        }

        $grandTotal = (clone $query)->count('id');

        return response()->json([
            'grand_total' => $grandTotal,
            'status_totals' => $totals
        ]);
    }

    public function getScheduledLoans(Request $request)
    {
        $scheduledLoans = DB::table('loan_application as la')
            ->join('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS full_name"),
                'lt.date as tenure_date',
                'la.referral'
            )
            ->where('la.loan_type', 'Scheduled')
            ->where('la.status', 1)
            ->where('la.loan_status', 5)
            ->orderBy('lt.date', 'asc')
            ->limit(5)
            ->get();

        return response()->json($scheduledLoans);
    }

    public function getRecentApplications(Request $request)
    {
        $recentApplications = DB::table('loan_application as la')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS full_name"),
                'la.created_at',
                'la.referral',
                'la.loan_amount',
                'la.loan_status'
            )
            ->where('la.status', 1)
            ->orderBy('la.created_at', 'asc')
            ->limit(5)
            ->get();

        return response()->json($recentApplications);
    }

   public function getRecentPayments(Request $request)
    {
        $recentPayments = DB::table('loan_payments as lp')
            ->join('users as u', 'u.id', '=', 'lp.added_by')
            ->join('loan_payment_types as lpt', 'lpt.id', '=', 'lp.payment_type_id')
            ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
            ->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS full_name"),
                'lp.amount_sent',
                'lp.reference_code',
                DB::raw("DATE_FORMAT(lp.created_at, '%M %d, %Y') as created_at"),
                'lpt.type as coverage',
                'lps.type as status'
            )
            ->orderBy('lp.created_at', 'desc') // recent first
            ->limit(5)
            ->get();

        // dd($recentPayments);

        return response()->json($recentPayments);
    }

    public function financialOverview(Request $request)
    {
        $filter = $request->input('filter', 'month'); 

        $interestQuery = DB::table('loan_tenure_interest as lti')
            ->join('loan_payments as lp', 'lp.id', '=', 'lti.payment_id')
            ->where('lp.payment_status_id', 3);

        $penaltyQuery = DB::table('loan_tenure_penalty as ltp')
            ->join('loan_payments as lp', 'lp.id', '=', 'ltp.payment_id')
            ->where('lp.payment_status_id', 3);

        // Apply filter
        if ($filter === 'week') {
            $interestQuery->whereBetween('lp.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $penaltyQuery->whereBetween('lp.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $interestQuery->whereMonth('lp.created_at', now()->month)
                        ->whereYear('lp.created_at', now()->year);
            $penaltyQuery->whereMonth('lp.created_at', now()->month)
                        ->whereYear('lp.created_at', now()->year);
        } elseif ($filter === 'year') {
            $interestQuery->whereYear('lp.created_at', now()->year);
            $penaltyQuery->whereYear('lp.created_at', now()->year);
        }

        $totalInterest = $interestQuery->sum('lti.interest');
        $totalPenalty = $penaltyQuery->sum('ltp.penalty');

        return response()->json([
            'interest_earned' => $totalInterest,
            'penalties_collected' => $totalPenalty,
            'revenue' => $totalInterest + $totalPenalty,
        ]);
    }

    public function topBorrowers(Request $request)
    {
        $filter = $request->input('filter', 'month'); // default filter

        $query = DB::table('loan_application as la')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->where('la.loan_status', 5);

        // Apply time filter
        if ($filter === 'week') {
            $query->whereBetween('la.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('la.created_at', now()->month)
                ->whereYear('la.created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('la.created_at', now()->year);
        }

        $topBorrowers = $query->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) as full_name"),
                'la.loan_amount',
                'la.created_at'
            )
            ->orderByDesc('la.loan_amount')
            ->limit(5)
            ->get();

        return response()->json($topBorrowers);
    }

    public function getLoanDates(Request $request)
    {
        $paymentId = $request->input('payment_id', 0); // default 9

        $loanDates = DB::table('loan_tenure as lt')
        ->join('loan_application as la', 'la.id', '=', 'lt.loan_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->leftJoin('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
        ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
        ->where('lt.payment_id', $paymentId)
        ->select(
            'lt.date',
            DB::raw('lt.principal + COALESCE(lti.interest,0) + COALESCE(ltp.penalty,0) as principal'),
            DB::raw("CONCAT(u.firstname, ' ', u.lastname) as name")
        )
        ->get();

        // Convert to FullCalendar events
        $events = $loanDates->map(function($item) {
            return [
                'title' => $item->name . ' ₱' . number_format($item->principal, 2),
                'start' => $item->date,
                'allDay' => true
            ];
        });

        return response()->json($events);
    }

    public function getBorrowerInsight(Request $request)
    {
        $filter = $request->input('filter', 'month'); // default filter

        // Total borrowers query
        $totalBorrowersQuery = DB::table('users')
            ->where('is_admin', 0)
            ->where('is_super_admin', 0)
            ->where('status', 1);

        // Active borrowers query
        $activeBorrowersQuery = DB::table('users as u')
            ->join('loan_application as la', 'la.loan_applicant', '=', 'u.id')
            ->where('u.is_admin', 0)
            ->where('u.is_super_admin', 0)
            ->where('u.status', 1)
            ->distinct('u.id');

        // Apply time filters
        if ($filter === 'week') {
            $activeBorrowersQuery->whereBetween('la.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $totalBorrowersQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $activeBorrowersQuery->whereMonth('la.created_at', now()->month)
                                ->whereYear('la.created_at', now()->year);
            $totalBorrowersQuery->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year);
        } elseif ($filter === 'year') {
            $activeBorrowersQuery->whereYear('la.created_at', now()->year);
            $totalBorrowersQuery->whereYear('created_at', now()->year);
        }

        // Execute queries
        $totalBorrowers = $totalBorrowersQuery->count();
        $activeBorrowers = $activeBorrowersQuery->count('u.id');

        return response()->json([
            'total_borrowers' => $totalBorrowers,
            'active_borrowers' => $activeBorrowers,
            'violations' => 0,
            'good_payer' => 0,
        ]);
    }

    public function LoanInsight(Request $request)
    {
        $filter = $request->input('filter', 'month'); // default filter

        // Base date range based on filter
        $startDate = null;
        $endDate = null;

        if ($filter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($filter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($filter === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        // Total disbursed loans
        $totalDisburseQuery = DB::table('loan_application')
            ->where('status', 1)
            ->where('loan_status', 5);

        if ($startDate && $endDate) {
            $totalDisburseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalDisburse = $totalDisburseQuery->sum('loan_amount');

        // Total balance (unpaid)
        $totalBalanceQuery = DB::table('loan_tenure as lt')
            ->leftJoin('loan_tenure_interest as lti', 'lt.id', '=', 'lti.tenure_id')
            ->where('lt.payment_id', 0);

        // if ($startDate && $endDate) {
        //     $totalBalanceQuery->whereBetween('lt.date', [$startDate, $endDate]);
        // }

        $totalBalance = $totalBalanceQuery->sum(DB::raw('lt.principal + IFNULL(lti.interest, 0)'));

        // Verified payments (paid and confirmed)
        $verifiedPaymentsQuery = DB::table('loan_tenure as lt')
            ->leftJoin('loan_tenure_interest as lti', 'lt.id', '=', 'lti.tenure_id')
            ->where('lt.payment_id', '!=', 0)
            ->where('lt.payment_status_id', 3);

        if ($startDate && $endDate) {
            $verifiedPaymentsQuery->whereBetween('lt.updated_at', [$startDate, $endDate]);
        }

        $verifiedPayments = $verifiedPaymentsQuery->sum(DB::raw('lt.principal + IFNULL(lti.interest, 0)'));

        // Upcoming payments in next 30 days (unpaid)
        $next30Days = now()->addDays(30);
        $upcomingBalanceQuery = DB::table('loan_tenure as lt')
            ->leftJoin('loan_tenure_interest as lti', 'lt.id', '=', 'lti.tenure_id')
            ->where('lt.payment_id', 0)
            ->where('lt.date', '<=', $next30Days);

        $upcomingBalance = $upcomingBalanceQuery->sum(DB::raw('lt.principal + IFNULL(lti.interest, 0)'));

        return response()->json([
            'total_disburse' => $totalDisburse,
            'total_balance' => $totalBalance,
            'verified_payments' => $verifiedPayments,
            'upcoming_balance' => $upcomingBalance,
        ]);
    }


    public function QuickStats(Request $request)
    {
        $filter = $request->input('filter', 'month'); // default filter

        // Determine date range based on filter
        if ($filter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($filter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($filter === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        //PENALTY (PAID + CONFIRMED)
        $penaltyQuery = DB::table('loan_tenure_penalty')
            ->where('payment_id', '!=', 0)
            ->where('payment_status_id', 3);

        if (isset($startDate) && isset($endDate)) {
            $penaltyQuery->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $totalPenalty = $penaltyQuery->sum('penalty');

        //INTEREST (PAID + CONFIRMED)
        $interestQuery = DB::table('loan_tenure_interest')
            ->where('payment_id', '!=', 0)
            ->where('payment_status_id', 3);

        if (isset($startDate) && isset($endDate)) {
            $interestQuery->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $totalInterest = $interestQuery->sum('interest');

        //COMBINE & TIGTHES
        $totalAmount = $totalPenalty + $totalInterest;
        $percentage_amount = $totalAmount * 0.10;

        return response()->json([
            'available_money' => 0,
            'balance' => 0,
            'tithes' => $percentage_amount,
            'misc' => $percentage_amount,
        ]);
    }













}
