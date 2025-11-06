<?php

namespace App\Http\Controllers;

use App\Models\loan\loan_application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.pages.customer.index');
    }

    public function getCpAll(Request $request)
    {
        // Existing loan applications
        $loan_application = DB::table('loan_application')
            ->select(
                DB::raw("(SELECT CONCAT(users.firstname, ' ', users.lastname) FROM users WHERE users.id = loan_application.loan_applicant) as borrower_name"),
                DB::raw("FORMAT(loan_application.loan_amount, 2) as loan_amount"),
                DB::raw("CONCAT(loan_application.loan_tenure, ' months') as loan_tenure"),
                "loan_application.loan_type",
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                DB::raw("CONCAT(UCASE(LEFT(loan_application.referral, 1)), LCASE(SUBSTRING(loan_application.referral, 2))) as referral"),
                DB::raw("CONCAT(FORMAT(loan_application.interest_rate, 2) * 100, '%') as interest_rate"),
                "loan_application.loan_status",
                DB::raw("(SELECT loan_status FROM loan_status WHERE loan_status.id = loan_application.loan_status LIMIT 1) as loan_status_by_name"),
                "loan_application.loan_applicant"
            )
            ->where('status', 1)
            ->get();

        // Get IDs of users that already have loans
        $existing_ids = $loan_application->pluck('loan_applicant')->toArray();

        // Users without loans (exclude existing loan applicants)
        $no_loans = DB::table('users as u')
            ->select(
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS borrower_name"),
                DB::raw("'-' AS loan_amount"),
                DB::raw("'-' AS loan_tenure"),
                DB::raw("'-' AS loan_type"),
                DB::raw("'-' AS created_at"),
                DB::raw("'-' AS referral"),
                DB::raw("'-' AS interest_rate"),
                DB::raw("'-' AS loan_status"),
                DB::raw("'No Loan Yet' AS loan_status_by_name"),
                DB::raw("u.id AS loan_applicant")
            )
            ->where('u.is_admin', 0)
            ->whereNotIn('u.id', $existing_ids)
            ->get();

        // Merge both collections (loan_application prioritized)
        $merged = $loan_application->merge($no_loans);

        return response()->json($merged);
    }



    public function getCpActive(Request $request)
    {

        $loan_application = DB::table('loan_application')
            ->select(
                DB::raw("(SELECT CONCAT(users.firstname, ' ', users.lastname) FROM users WHERE users.id = loan_application.loan_applicant) as borrower_name"),
                DB::raw("FORMAT(loan_application.loan_amount, 2) as loan_amount"),
                DB::raw("CONCAT(loan_application.loan_tenure, ' months') as loan_tenure"),
                "loan_application.loan_type",
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                DB::raw("CONCAT(UCASE(LEFT(loan_application.referral, 1)), LCASE(SUBSTRING(loan_application.referral, 2))) as referral"),
                DB::raw("CONCAT(FORMAT(loan_application.interest_rate, 2) * 100, '%') as interest_rate"),
                "loan_application.loan_status",
                DB::raw("(SELECT loan_status FROM loan_status WHERE loan_status.id = loan_application.loan_status LIMIT 1) as loan_status_by_name"),
                "loan_application.loan_applicant"
            )
            ->where('status', 1)
            ->where('loan_type', 'Express')
            ->where('loan_application.loan_status', 5)
            ->get();

        return response()->json($loan_application);
    }

    public function getCpScheduled(Request $request)
    {

        $loan_application = DB::table('loan_application')
            ->select(
                DB::raw("(SELECT CONCAT(users.firstname, ' ', users.lastname) FROM users WHERE users.id = loan_application.loan_applicant) as borrower_name"),
                DB::raw("FORMAT(loan_application.loan_amount, 2) as loan_amount"),
                DB::raw("CONCAT(loan_application.loan_tenure, ' months') as loan_tenure"),
                "loan_application.loan_type",
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                DB::raw("CONCAT(UCASE(LEFT(loan_application.referral, 1)), LCASE(SUBSTRING(loan_application.referral, 2))) as referral"),
                DB::raw("CONCAT(FORMAT(loan_application.interest_rate, 2) * 100, '%') as interest_rate"),
                "loan_application.loan_status",
                DB::raw("(SELECT loan_status FROM loan_status WHERE loan_status.id = loan_application.loan_status LIMIT 1) as loan_status_by_name"),
                "loan_application.loan_applicant"
            )
            ->where('status', 1)
            ->where('loan_type', 'Scheduled')
            ->where('loan_application.loan_status', 4)
            ->get();

        return response()->json($loan_application);
    }

    public function cpasViewMoreInfo(Request $request)
    {
        $user_id = $request->user_id;
        $tab_type = $request->tab_type;

        $user_info = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                // "users.created_at",
                DB::raw("DATE_FORMAT(users.created_at, '%b %d, %Y') as created_at"),
                DB::raw("CONCAT(user_details.house_no, ', ', user_details.street, ', ', user_details.barangay, ', ', user_details.city, ', ', user_details.province) as address"),
                "users.contactno",
                "users.email",

            )
            // ->where('users.status', 1)
            ->where('users.id', $user_id)
            ->first();
        if ($tab_type !== 0) {
            $loan_application = DB::table('loan_application')
                ->select(
                    "id"
                )
                ->where('loan_application.status', 1)
                ->where('loan_application.loan_applicant', $user_id)
                ->get();

            $active_loan = DB::table('loan_application')
                ->select(
                    "id",
                    DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                    DB::raw("FORMAT(loan_application.loan_amount, 2) as loan_amount"),
                    DB::raw("CONCAT(loan_application.loan_tenure, ' months') as loan_tenure"),
                    DB::raw("CONCAT(UCASE(LEFT(loan_application.referral, 1)), LCASE(SUBSTRING(loan_application.referral, 2))) as referral"),
                    "loan_application.loan_status",
                    DB::raw("(SELECT loan_status FROM loan_status WHERE loan_status.id = loan_application.loan_status LIMIT 1) as loan_status_by_name"),
                )
                ->where('loan_application.status', 1)
                // ->where('loan_application.loan_status', operator: 5)
                ->where('loan_application.loan_applicant', $user_id)
                ->first();

            $loan_payments = DB::table('loan_payments')
                ->select(DB::raw("DATE_FORMAT(created_at, '%b %d, %Y') as created_at"))
                ->where('loan_application_id', $active_loan->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $last_payments = $loan_payments->map(function ($payment) {
                return \Carbon\Carbon::parse($payment->created_at)->format('M d, Y');
            })->implode('<br>');

            $next_payment = DB::table('loan_tenure')
                ->select(DB::raw("DATE_FORMAT(date, '%b %d, %Y') as date"))
                ->where('payment_id', 0)
                ->where('loan_id', $active_loan->id)
                ->first();


            $loan_tenure_ids = DB::table('loan_tenure')
                ->where('loan_id', $active_loan->id)
                ->pluck('id')
                ->toArray();

            $loan_tenure = DB::table('loan_tenure')
                ->where('payment_id', 0)
                ->where('loan_id', $active_loan->id)
                ->sum('principal');

            $loan_tenure_interest = DB::table('loan_tenure_interest')
                ->where('payment_id', 0)
                ->whereIn('tenure_id', $loan_tenure_ids) // eto yung IN
                ->sum('interest');

            $outstandingBalance = $loan_tenure + $loan_tenure_interest;
            $outstandingBalance = number_format($outstandingBalance, 2);

            $loan_count = $loan_application->count();
            $active_loan_id = $active_loan->id;
        }

        $final_output = array(
            "user_info" => $user_info,
            "loan_applications" => $active_loan ?? '-',
            "next_payment_date" => $next_payment->date ?? '-',
            "last_payments_date" => $last_payments ?? '-',
            "total_loan_taken" => $loan_count ?? 0,
            "outstanding_balance" => $outstandingBalance ?? 0,
            "payment_history" => $this->paymentHistory(1, $active_loan_id ?? 0)
        );

        return response()->json($final_output);
    }

    public function cpaPaymentDetails(Request $request)
    {
        $payment_id = $request->payment_id;

        $data = $this->paymentHistory(2, $payment_id);
        return response()->json($data);
    }

    public function paymentHistory($type, $id)
    {
        # TYPES
        # 1 - borrower information
        # 2 - payment details

        $condition = $type == 1 ? "lp.loan_application_id" : "lp.id";

        $payment_history = DB::table('loan_payments as lp')
            ->leftjoin('loan_tenure as lt', 'lp.id', '=', 'lt.payment_id')
            ->leftjoin('loan_tenure_interest as lti', 'lt.id', '=', 'lti.tenure_id')
            ->leftjoin('loan_tenure_penalty as ltp', 'lt.id', '=', 'ltp.tenure_id')
            ->select(
                "lp.id as payment_id",
                "lp.reference_code",
                DB::raw("DATE_FORMAT(lp.created_at, '%b %d, %Y') as payment_date"),
                DB::raw("FORMAT(lt.principal, 2) as principal"),
                DB::raw("FORMAT(lti.interest, 2) as interest"),
                DB::raw("FORMAT(ltp.penalty, 2) as penalty"),
                DB::raw("FORMAT(lp.amount_sent, 2) as amount_sent"),
                DB::raw("CONCAT('₱', FORMAT(lt.principal, 2), ' / ₱', FORMAT(lti.interest, 2), ' / ₱', FORMAT(ltp.penalty, 2)) as break_down"),
                DB::raw("DATE_FORMAT(lt.date, '%M %Y') as coverage_month"),
                "lp.remarks",
                "lp.attachment"
            )
            ->whereRaw("$condition = ?", [$id])
            ->get();

        return $payment_history;
    }
}
