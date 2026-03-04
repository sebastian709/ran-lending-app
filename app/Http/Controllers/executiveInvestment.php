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

class executiveInvestment extends Controller
{
    private function getExecutiveFundSnapshot(): object
    {
        return DB::selectOne("
            SELECT
                SUM(remaining) AS remaining,
                SUM(interest) AS interest,
                SUM(penalty) AS penalty,
                SUM(paid) AS paid,
                SUM(misc) AS misc,
                SUM(tithes) AS tithes,
                IFNULL(money, 0) AS money,
                ((IFNULL(money, 0) - IFNULL(SUM(remaining), 0)) + IFNULL(SUM(misc), 0)) AS remaining_money
            FROM (
                SELECT
                    (SELECT IFNULL(SUM(principal), 0) FROM loan_tenure WHERE loan_id = la.id AND payment_id = 0) AS remaining,
                    (SELECT IFNULL(SUM(principal), 0) FROM loan_tenure WHERE loan_id = la.id AND payment_id != 0) AS paid,
                    (
                        SELECT
                            IFNULL(SUM(IF(alti.payment_id = 0 OR alti.payment_status_id = 4, 0, interest)), 0) +
                            IFNULL(SUM(IF(altp.penalty = 0, 0, penalty)), 0)
                        FROM loan_tenure alt
                        INNER JOIN loan_tenure_interest alti ON alti.tenure_id = alt.id
                        LEFT JOIN loan_tenure_penalty altp ON altp.tenure_id = alt.id
                        WHERE alt.loan_id = la.id
                    ) AS misc,
                    (
                        SELECT IFNULL(SUM(IF(alti.payment_id = 0 OR alti.payment_status_id = 4, 0, interest)), 0)
                        FROM loan_tenure alt
                        INNER JOIN loan_tenure_interest alti ON alti.tenure_id = alt.id
                        WHERE alt.loan_id = la.id
                    ) AS interest,
                    (
                        SELECT IFNULL(SUM(IF(altp.penalty = 0, 0, penalty)), 0)
                        FROM loan_tenure alt
                        INNER JOIN loan_tenure_interest alti ON alti.tenure_id = alt.id
                        LEFT JOIN loan_tenure_penalty altp ON altp.tenure_id = alt.id
                        WHERE alt.loan_id = la.id
                    ) AS penalty,
                    (
                        (
                            SELECT
                                IFNULL(SUM(IF(alti.payment_id = 0 OR alti.payment_status_id = 4, 0, interest)), 0) +
                                IFNULL(SUM(IF(altp.penalty = 0, 0, penalty)), 0)
                            FROM loan_tenure alt
                            INNER JOIN loan_tenure_interest alti ON alti.tenure_id = alt.id
                            LEFT JOIN loan_tenure_penalty altp ON altp.tenure_id = alt.id
                            WHERE alt.loan_id = la.id
                        ) * 0.10
                    ) AS tithes,
                    (SELECT IFNULL(SUM(amount), 0) FROM executive_account_balance) AS money,
                    la.*
                FROM loan_application la
            ) a
        ");
    }

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
          return view('admin.pages.executive.executive');
    }

    public function add_investment(Request $request)
    {   
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0',
            'remarks' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:4096',
        ]);

        $userId = auth()->id();
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = uniqid('exec_inv_') . '.' . $file->getClientOriginalExtension();
            $directory = public_path('storage/uploads/executive_attachments');

            if (!file_exists($directory)) {
                mkdir($directory, 0775, true);
            }

            $file->move($directory, $filename);
            $attachmentPath = 'uploads/executive_attachments/' . $filename;
        }

        DB::table('executive_account_balance')->insert([
            'amount' => $validated['amount'],
            'category' => 1,
            'remarks' => $validated['remarks'] ?? null,
            'attachment' => $attachmentPath,
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added successfully!',
        ]);
    }

    public function withraw_investment(Request $request)
    {   
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0',
            'reason' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();
        $snapshot = $this->getExecutiveFundSnapshot();
        $available = (float) ($snapshot->remaining_money ?? 0);
        $withdrawAmount = (float) $validated['amount'];

        if ($available < $withdrawAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient available funds for withdrawal.',
            ], 422);
        }

          DB::table('executive_account_balance')->insert([
            'amount' => -abs($withdrawAmount),
            'category' => 2,
            'remarks' => $validated['reason'],
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added successfully!',
        ]);
    }


    public function pull_data(Request $request)
    {
        $userId = auth()->id();
        $data['hide_money'] = DB::selectOne('SELECT hide_money from users where id = ?',[$userId]); //shareds fund
        $data['total_fund'] = DB::selectOne('SELECT IFNULL(sum(amount),0) amount from executive_account_balance'); // net fund (includes withdrawals)
        $data['withrawn_fund'] = DB::selectOne('SELECT ABS(IFNULL(sum(amount),0)) amount from executive_account_balance where category = 2'); // total withdrawn
        $data['shared_fund'] = DB::selectOne('SELECT IFNULL(sum(amount),0) amount from executive_account_balance where created_by = ?',[$userId]); // net personal contribution
        // dd($data);

        $data['fund_management'] = DB::select("SELECT eab.*,concat(firstname,' ',lastname) name,
                                    IF(category = 1,'Add Investment', IF(category = 2, 'Withdraw Fund', 'Adjustment')) categories,
                                    DATE_FORMAT(eab.created_at, '%b %d, %Y, %h:%i %p') readable_date
                                    from executive_account_balance eab
                                    inner join users u on u.id = eab.created_by order by eab.id desc"); //Fund Management

        $data['data'] = $this->getExecutiveFundSnapshot();

        //GET DIVIDEND
        $totalFund = (float) ($data['total_fund']->amount ?? 0);
        $sharedFund = (float) ($data['shared_fund']->amount ?? 0);
        $data['dividendpercent'] = $totalFund > 0 ? ($sharedFund / $totalFund) * 100 : 0;
        
            


        return response()->json([
            'data' => $data
        ]);
    }
    public function money_status(Request $request){
        $userId = auth()->id();
        $hide_money = DB::selectOne('SELECT hide_money  from users where id = ?',[$userId]); 

        if((int) $hide_money->hide_money === 1){
            $status = 0;
        }else{
            $status = 1;
        }
        user::where('id',  $userId)
        ->update([
            'hide_money' => $status,
        ]);
        
        return response()->json([
            'status' => $status
        ]);
    }


}
