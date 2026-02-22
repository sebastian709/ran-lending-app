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
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || (int) auth()->user()->is_admin !== 1) {
                abort(403);
            }

            return $next($request);
        });
    }

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
        $query = DB::table('loan_application')->where('loan_status', '!=', 0);

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

    // public function financialOverview(Request $request)
    // {   
    //     // dd( now()->year);
    //     $filter = $request->input('filter', 'month'); 

    //     $interestQuery = DB::table('loan_tenure_interest as lti')
    //         ->join('loan_payments as lp', 'lp.id', '=', 'lti.payment_id')
    //         ->where(function ($query) {
    //             $query->where('lti.payment_id', '>',0);
    //                 ->orWhere('lti.payment_status_id', 4);
    //         });


    //     $penaltyQuery = DB::table('loan_tenure_penalty as ltp')
    //         ->join('loan_payments as lp', 'lp.id', '=', 'ltp.payment_id')
    //         ->where('ltp.payment_id','>', 0);

    //     // Apply filter
    //     if ($filter === 'week') {
    //         $interestQuery->whereBetween('lp.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    //         $penaltyQuery->whereBetween('lp.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    //     } elseif ($filter === 'month') {
    //         $interestQuery->whereMonth('lp.created_at', now()->month)
    //                     ->whereYear('lp.created_at', now()->year);
    //         $penaltyQuery->whereMonth('lp.created_at', now()->month)
    //                     ->whereYear('lp.created_at', now()->year);
    //     } elseif ($filter === 'year') {
    //         $interestQuery->whereYear('lp.created_at', now()->year);
    //         $penaltyQuery->whereYear('lp.created_at', now()->year);
    //     }
    //     // dd(now()->year,now()->month  );
    //     $totalInterest = $interestQuery->sum('lti.interest');
    //     $totalPenalty = $penaltyQuery->sum('ltp.penalty');

    //     return response()->json([
    //         'interest_earned' => $totalInterest,
    //         'penalties_collected' => $totalPenalty,
    //         'revenue' => $totalInterest + $totalPenalty,
    //     ]);
    // }


    public function financialOverview(Request $request)
    {
        $filter = $request->input('filter', 'month');

        // Date conditions
        $interestDateSql = '';
        $penaltyDateSql  = '';
        $bindings = [];

        if ($filter === 'week') {
            $start = now()->startOfWeek()->format('Y-m-d H:i:s');
            $end   = now()->endOfWeek()->format('Y-m-d H:i:s');

            $interestDateSql = " AND lp.created_at BETWEEN ? AND ? ";
            $penaltyDateSql  = " AND lp.created_at BETWEEN ? AND ? ";

            $bindings = [$start, $end];

        } elseif ($filter === 'month') {
            $interestDateSql = " AND MONTH(lp.created_at) = ? AND YEAR(lp.created_at) = ? ";
            $penaltyDateSql  = " AND MONTH(lp.created_at) = ? AND YEAR(lp.created_at) = ? ";

            $bindings = [now()->month, now()->year];

        } elseif ($filter === 'year') {
            $interestDateSql = " AND YEAR(lp.created_at) = ? ";
            $penaltyDateSql  = " AND YEAR(lp.created_at) = ? ";

            $bindings = [now()->year];
        }

        /* ==========================
        TOTAL INTEREST
        ========================== */
        $interestSql = "
            SELECT SUM(lti.interest) AS total_interest
            FROM loan_tenure_interest AS lti
            JOIN loan_payments AS lp ON lp.id = lti.payment_id
            WHERE (lti.payment_id > 0 and lti.payment_status_id != 4)
            $interestDateSql
        ";

        $totalInterest = DB::select($interestSql, $bindings)[0]->total_interest ?? 0;

        /* ==========================
        TOTAL PENALTY
        ========================== */
        $penaltySql = "
            SELECT SUM(ltp.penalty) AS total_penalty
            FROM loan_tenure_penalty AS ltp
            JOIN loan_payments AS lp ON lp.id = ltp.payment_id
            WHERE ltp.payment_id > 0
            $penaltyDateSql
        ";

        $totalPenalty = DB::select($penaltySql, $bindings)[0]->total_penalty ?? 0;

        return response()->json([
            'interest_earned'      => (float) $totalInterest,
            'penalties_collected'  => (float) $totalPenalty,
            'revenue'              => (float) ($totalInterest + $totalPenalty),
        ]);
    }


    public function topBorrowers(Request $request)
    {
        $filter = $request->input('filter', 'month'); // default filter

        $query = DB::table('loan_application as la')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->where('la.loan_status', 5);

        // // Apply time filter
        // if ($filter === 'week') {
        //     $query->whereBetween('la.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        // } elseif ($filter === 'month') {
        //     $query->whereMonth('la.created_at', now()->month)
        //         ->whereYear('la.created_at', now()->year);
        // } elseif ($filter === 'year') {
        //     $query->whereYear('la.created_at', now()->year);
        // }

        // $topBorrowers = $query->select(
        //         DB::raw("CONCAT(u.firstname, ' ', u.lastname) as full_name"),
        //         'la.loan_amount',
        //         'la.created_at'
        //     )
        //     ->orderByDesc('la.loan_amount')
        //     ->limit(5)
        //     ->get();


        $query = DB::table('loan_application as la')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->whereIn('la.loan_status', [5, 7]);

        // Apply time filter
        if ($filter === 'week') {
            $query->whereBetween('la.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('la.created_at', now()->month)
                ->whereYear('la.created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('la.created_at', now()->year);
        }

        $topBorrowers = $query->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS full_name"),
                DB::raw("SUM(la.loan_amount) AS loan_amount")
            )
            ->groupBy('la.loan_applicant', 'u.firstname', 'u.lastname')
            ->orderByDesc('loan_amount')
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
        $totalBorrowersQuery = DB::selectOne(query: "SELECT count(loan_applicant) total from (select loan_applicant from loan_application where status = 1 group by loan_applicant) a;");

        // Active borrowers query
        // $data = DB::select("SELECT 
        //                     loan_id, 
        //                     count(a.count),
        //                     sum(has_penalty) ,
        //                     ROUND((sum(has_penalty) / count(a.count)) * 100, 2) percentage,
        //                     has_penalty
        //                 from 
        //                 (select *,(select if(count(id) > 0,1,0) from loan_tenure_penalty where tenure_id = lt.id) has_penalty
        //                 from loan_tenure lt) a
        //                 inner join loan_application la on la.id = a.loan_id
        //                 group by la.loan_applicant
        //             ;");

        $data = DB::select("SELECT sum((select if(count(id) > 0,1,0) has_penalty from loan_tenure_penalty where tenure_id = lt.id)) has_penalty
                        from loan_tenure lt
                        inner join loan_application la on la.id = lt.loan_id
                        group by la.loan_applicant");

        $rows = collect($data);

        $no_penalty_count = $rows->where('has_penalty', 0)->count();
        $penalty_count = $rows->where('has_penalty', '>', 0)->count();
        

        //==============================

        $active_borrower = DB::selectOne(" SELECT COUNT(DISTINCT loan_applicant) AS total FROM loan_application WHERE loan_status = 5
        ")->total;

        $paymentss = DB::select("SELECT
                            loan_id, 
                            count(a.count),
                            sum(has_penalty) ,
                            ROUND((sum(has_penalty) / count(a.count)) * 100, 2) percentage,
                            has_penalty
                        from 
                        (
							select lt.*,
                            (select if(count(id) > 0,1,0) from loan_tenure_penalty where tenure_id = lt.id) has_penalty
							from loan_tenure lt
							inner join loan_application la on la.id = lt.loan_id and la.loan_status = 5
						) a
                        group by loan_id;
                        ");
        $rowss = collect($paymentss);
        $ontime_payment = $rowss->where('has_penalty', 0)->count();
        $late_payment = $rowss->where('has_penalty', '>', 0)->count();

        // $partial_payment

        $partial = DB::select("SELECT 
                            loan_id, 
                            count(a.count),
                            sum(has_penalty) ,
                            ROUND((sum(has_penalty) / count(a.count)) * 100, 2) percentage,
                            has_penalty

                        from 
                        (select *,(select if(count(id) > 0,1,0) from loan_tenure_penalty where tenure_id = lt.id) has_penalty
                        from loan_tenure lt) a
                        inner join loan_application la on la.id = a.loan_id and la.loan_status = 5
                        inner join loan_payments lp on lp.loan_application_id = la.id
                        inner join loan_payment_types lpt on lpt.id =  lp.payment_type_id
                        
                        where lp.payment_type_id = 2
                        group by la.loan_applicant
                        ");
        $rowss = collect($partial);
        $partial_payment = $rowss->where('loan_id', '>', 0)->count();

        $redFlag = DB::selectOne("SELECT count(red_flag) redflag from loan_application where red_flag = 1");


        return response()->json([
            'total_borrowers' => $totalBorrowersQuery->total,
            'violations' => $redFlag->redflag,
            'penalty' => $penalty_count,
            'good_payer' => $no_penalty_count,
            'active_borrower' => $active_borrower,
            'ontime_payment' => $ontime_payment,
            'late_payment' => $late_payment,
            'partial_payment' => $partial_payment,
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


        $data = DB::selectOne("SELECT  sum(remaining) remaining,sum(paid) paid,sum(misc) misc,sum(tithes) tithes,money,((IFNULL(money,0) - IFNULL(SUM(remaining),0)) + IFNULL(SUM(misc),0)) remaining_money from (select 
                (select ifnull(sum(principal),0) from loan_tenure where loan_id = la.id and payment_id = 0) remaining ,
                (select ifnull(sum(principal),0) from loan_tenure where loan_id = la.id and payment_id != 0) paid ,
                (select ifnull(sum(if(alti.payment_id = 0 or alti.payment_status_id = 4,0,interest)),0) + ifnull(sum(if(altp.penalty = 0,0,penalty)),0)
                    from loan_tenure alt 
                    inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                    left join loan_tenure_penalty altp on altp.tenure_id = alt.id 
                    where alt.loan_id = la.id 
                ) misc,
                (select ifnull(sum(if(alti.payment_id = 0 or alti.payment_status_id = 4,0,interest)),0) + ifnull(sum(if(altp.penalty = 0,0,penalty)),0)
                    from loan_tenure alt 
                    inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                    left join loan_tenure_penalty altp on altp.tenure_id = alt.id 
                    where alt.loan_id = la.id 
                ) * 0.10 tithes,
                (select sum(amount) from executive_account_balance where category = 1) money,
                la.* 
                from loan_application la ) a");



        return response()->json([
            'available_money' => $data->remaining_money,
            'balance' => $data->remaining,
            'tithes' => $data->tithes,
            'misc' => $data->misc,
        ]);
    }


    public function exportPDF(Request $request)
    {
        $filter = $request->input('filter', 'month');
        $dataRequest = new Request(['filter' => $filter]);
        $readJson = static fn($response) => $response->getData(true);

        $applicationSummary = $readJson($this->getTotalApplications($dataRequest));
        $scheduledLoans = collect($readJson($this->getScheduledLoans($dataRequest)));
        $recentApplications = collect($readJson($this->getRecentApplications($dataRequest)));
        $recentPayments = collect($readJson($this->getRecentPayments($dataRequest)));
        $financialOverview = $readJson($this->financialOverview($dataRequest));
        $topBorrowers = collect($readJson($this->topBorrowers($dataRequest)));
        $borrowerInsight = $readJson($this->getBorrowerInsight($dataRequest));
        $loanInsight = $readJson($this->LoanInsight($dataRequest));
        $quickStats = $readJson($this->QuickStats($dataRequest));

        $data = [
            'grand_total' => $applicationSummary['grand_total'] ?? 0,
            'status_totals' => $applicationSummary['status_totals'] ?? [],
            'scheduled_loans' => $scheduledLoans,
            'recent_applications' => $recentApplications,
            'recent_payments' => $recentPayments,
            'total_interest' => (float) ($financialOverview['interest_earned'] ?? 0),
            'total_penalty' => (float) ($financialOverview['penalties_collected'] ?? 0),
            'top_borrowers' => $topBorrowers,
            'borrower_insight' => $borrowerInsight,
            'loan_insight' => $loanInsight,
            'quick_stats' => $quickStats,
        ];

        $pdf = Pdf::loadView('admin.exports.loan_export', $data);
        return $pdf->download('dashboard_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter', 'month');
        $dataRequest = new Request(['filter' => $filter]);
        $readJson = static fn($response) => $response->getData(true);

        $statuses = [
            1 => 'pending', 2 => 'for_interview', 3 => 'for_revision',
            4 => 'waiting', 5 => 'transferred', 6 => 'rejected',
            7 => 'closed', 8 => 'scheduled', 9 => 'cancelled'
        ];

        $applicationSummary = $readJson($this->getTotalApplications($dataRequest));
        $statusTotals = $applicationSummary['status_totals'] ?? [];

        $scheduledLoans = collect($readJson($this->getScheduledLoans($dataRequest)));
        $scheduledLoansData = $scheduledLoans->map(fn($item) => [
            'Borrower' => is_array($item) ? $item['full_name'] : $item->full_name,
            'Tenure Date' => is_array($item) ? $item['tenure_date'] : $item->tenure_date,
            'Referral' => is_array($item) ? $item['referral'] : $item->referral
        ])->toArray();

        $recentApplications = collect($readJson($this->getRecentApplications($dataRequest)));

        $recentApplicationsData = $recentApplications->map(fn($item) => [
            'Borrower' => is_array($item) ? $item['full_name'] : $item->full_name,
            'Date' => is_array($item) ? $item['created_at'] : $item->created_at,
            'Referral' => is_array($item) ? $item['referral'] : $item->referral,
            'Amount' => is_array($item) ? $item['loan_amount'] : $item->loan_amount,
            'Status' => $statuses[is_array($item) ? $item['loan_status'] : $item->loan_status] ?? 'Unknown'
        ])->toArray();

        $recentPayments = collect($readJson($this->getRecentPayments($dataRequest)));

        $recentPaymentsData = $recentPayments->map(fn($item) => [
            'Added By' => is_array($item) ? $item['full_name'] : $item->full_name,
            'Amount' => is_array($item) ? $item['amount_sent'] : $item->amount_sent,
            'Reference' => is_array($item) ? $item['reference_code'] : $item->reference_code,
            'Date' => is_array($item) ? $item['created_at'] : $item->created_at,
            'Coverage' => is_array($item) ? $item['coverage'] : $item->coverage,
            'Status' => is_array($item) ? $item['status'] : $item->status
        ])->toArray();

        $topBorrowers = collect($readJson($this->topBorrowers($dataRequest)));

        $topBorrowersData = $topBorrowers->map(fn($item) => [
            'Borrower' => is_array($item) ? $item['full_name'] : $item->full_name,
            'Amount' => is_array($item) ? $item['loan_amount'] : $item->loan_amount
        ])->toArray();

        $borrowerInsight = $readJson($this->getBorrowerInsight($dataRequest));
        $loanInsight = $readJson($this->LoanInsight($dataRequest));
        $quickStats = $readJson($this->QuickStats($dataRequest));
        $financialOverview = $readJson($this->financialOverview($dataRequest));

        // $quickStats = [
        //     'available_money'=>0,
        //     'balance'=>0,
        //     'tithes'=>($totalInterest+$totalPenalty)*0.10,
        //     'misc'=>($totalInterest+$totalPenalty)*0.10
        // ];

        // --- Financial overview (same source logic as dashboard API) ---
        $interestDateSql = '';
        $penaltyDateSql = '';
        $bindings = [];
        if ($filter === 'week') {
            $interestDateSql = " AND lp.created_at BETWEEN ? AND ? ";
            $penaltyDateSql = " AND lp.created_at BETWEEN ? AND ? ";
            $bindings = [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')];
        } elseif ($filter === 'month') {
            $interestDateSql = " AND MONTH(lp.created_at) = ? AND YEAR(lp.created_at) = ? ";
            $penaltyDateSql = " AND MONTH(lp.created_at) = ? AND YEAR(lp.created_at) = ? ";
            $bindings = [now()->month, now()->year];
        } elseif ($filter === 'year') {
            $interestDateSql = " AND YEAR(lp.created_at) = ? ";
            $penaltyDateSql = " AND YEAR(lp.created_at) = ? ";
            $bindings = [now()->year];
        }

        $totalInterest = (float) (DB::select("
            SELECT SUM(lti.interest) AS total_interest
            FROM loan_tenure_interest AS lti
            JOIN loan_payments AS lp ON lp.id = lti.payment_id
            WHERE (lti.payment_id > 0 and lti.payment_status_id != 4)
            $interestDateSql
        ", $bindings)[0]->total_interest ?? 0);

        $totalPenalty = (float) (DB::select("
            SELECT SUM(ltp.penalty) AS total_penalty
            FROM loan_tenure_penalty AS ltp
            JOIN loan_payments AS lp ON lp.id = ltp.payment_id
            WHERE ltp.payment_id > 0
            $penaltyDateSql
        ", $bindings)[0]->total_penalty ?? 0);

        $financialOverview = [
            'interest_earned' => $totalInterest,
            'penalties_collected' => $totalPenalty,
            'revenue' => $totalInterest + $totalPenalty,
        ];

        // --- Excel Generation ---
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Dashboard Report');
        $row = 1;

        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => '40739e']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $styleTableHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '40739e']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $styleTableRow = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $getValue = function($item, $header) {
            if (is_array($item) && isset($item[$header])) return $item[$header];
            if (is_object($item) && isset($item->$header)) return $item->$header;
            return '';
        };

        $addSectionTable = function($title, $headers, $data) use (&$sheet, &$row, $getValue, $styleHeader, $styleTableHeader, $styleTableRow) {
            $sheet->mergeCells("A$row:" . Coordinate::stringFromColumnIndex(count($headers)) . "$row");
            $sheet->setCellValue("A$row", $title);
            $sheet->getStyle("A$row")->applyFromArray($styleHeader);
            $row++;

            foreach ($headers as $col => $header) {
                $cell = Coordinate::stringFromColumnIndex($col + 1) . $row;
                $sheet->setCellValue($cell, ucfirst($header));
            }
            $sheet->getStyle("A$row:" . Coordinate::stringFromColumnIndex(count($headers)) . "$row")->applyFromArray($styleTableHeader);
            $row++;

            foreach ($data as $item) {
                foreach ($headers as $col => $header) {
                    $cell = Coordinate::stringFromColumnIndex($col + 1) . $row;
                    $sheet->setCellValue($cell, $getValue($item, $header));
                }
                $sheet->getStyle("A$row:" . Coordinate::stringFromColumnIndex(count($headers)) . "$row")->applyFromArray($styleTableRow);
                $row++;
            }
            $row++;
        };

        // --- Add sections ---
        $quickStatsData = collect($quickStats)->map(fn($v,$k)=>['Metric'=>$k,'Value'=>$v])->toArray();
        $addSectionTable('Quick Stats', ['Metric','Value'], $quickStatsData);

        $statusTotalsData = collect($statusTotals)->map(fn($v,$k)=>['Status'=>$k,'Count'=>$v])->toArray();
        $addSectionTable('Loan Applications', ['Status','Count'], $statusTotalsData);

        $addSectionTable('Scheduled Loans', ['Borrower','Tenure Date','Referral'], $scheduledLoansData);
        $addSectionTable('Recent Applications', ['Borrower','Date','Referral','Amount','Status'], $recentApplicationsData);
        $addSectionTable('Recent Payments', ['Added By','Amount','Reference','Date','Coverage','Status'], $recentPaymentsData);
        $addSectionTable('Loan Insight', ['Metric','Value'], collect($loanInsight)->map(fn($v,$k)=>['Metric'=>$k,'Value'=>$v])->toArray());
        $addSectionTable('Borrower Insight', ['Metric','Value'], collect($borrowerInsight)->map(fn($v,$k)=>['Metric'=>$k,'Value'=>$v])->toArray());
        $addSectionTable('Top 5 Borrowers', ['Borrower','Amount'], $topBorrowersData);
        $addSectionTable('Financial Overview', ['Metric','Value'], collect($financialOverview)->map(fn($v,$k)=>['Metric'=>$k,'Value'=>$v])->toArray());

        // --- Auto-size columns ---
        foreach(range('A',$sheet->getHighestColumn()) as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Export Excel ---
        $filename = 'dashboard_report.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }












}
