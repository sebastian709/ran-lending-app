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
        $userId = auth()->id();

        DB::table('executive_account_balance')->insert([
            'amount' => $request->amount,
            'category' => 1,
            'remarks' => $request->remarks,
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
        $userId = auth()->id();

          DB::table('executive_account_balance')->insert([
            'amount' => -abs($request->amount),
            'category' => 2,
            'remarks' => $request->reason,
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
        $data['total_fund'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where category = 1'); //total fund
        $data['withrawn_fund'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where category = 2'); //total fund
        $data['shared_fund'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where created_by = ? and  category = 1',[$userId]); //shareds fund
        // dd($data);

        $data['fund_management'] = DB::select("SELECT eab.*,concat(firstname,' ',lastname) name,
                                    if(category = 1,'Lending Fund','Shared Fund') categories,
                                    DATE_FORMAT(eab.created_at, '%b %d, %Y, %h:%i %p') readable_date
                                    from executive_account_balance eab
                                    inner join users u on u.id = eab.created_by order by eab.id desc"); //Fund Management

        $data['data'] = DB::selectOne("SELECT  sum(remaining) remaining,sum(interest) interest,sum(penalty) penalty,sum(paid) paid,sum(misc) misc,sum(tithes) tithes,money,((IFNULL(money,0) - IFNULL(SUM(remaining),0)) + IFNULL(SUM(misc),0)) remaining_money from (select 
            (select ifnull(sum(principal),0) from loan_tenure where loan_id = la.id and payment_id = 0) remaining ,
            (select ifnull(sum(principal),0) from loan_tenure where loan_id = la.id and payment_id != 0) paid ,
            (select ifnull(sum(if(alti.payment_id = 0 or alti.payment_status_id = 4,0,interest)),0) + ifnull(sum(if(altp.penalty = 0,0,penalty)),0)
                from loan_tenure alt 
                inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                left join loan_tenure_penalty altp on altp.tenure_id = alt.id 
                where alt.loan_id = la.id 
            ) misc,
            (select ifnull(sum(if(alti.payment_id = 0 or alti.payment_status_id = 4,0,interest)),0)
                from loan_tenure alt 
                inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                where alt.loan_id = la.id 
            ) interest,
            (select ifnull(sum(if(altp.penalty = 0,0,penalty)),0)
                from loan_tenure alt 
                inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                left join loan_tenure_penalty altp on altp.tenure_id = alt.id 
                where alt.loan_id = la.id 
            ) penalty,
            (select ifnull(sum(if(alti.payment_id = 0 or alti.payment_status_id = 4,0,interest)),0) + ifnull(sum(if(altp.penalty = 0,0,penalty)),0)
                from loan_tenure alt 
                inner join loan_tenure_interest alti on alti.tenure_id = alt.id
                left join loan_tenure_penalty altp on altp.tenure_id = alt.id 
                where alt.loan_id = la.id 
            ) * 0.10 tithes,
            (select sum(amount) from executive_account_balance where category = 1) money,
            la.* 
            from loan_application la ) a");

        //GET DIVIDEND

        $data['dividendpercent'] = ($data['shared_fund']->amount / $data['total_fund']->amount) * 100;
        
            


        return response()->json([
            'data' => $data
        ]);
    }
    public function money_status(Request $request){
        $userId = auth()->id();
        $hide_money = DB::selectOne('SELECT hide_money  from users where id = ?',[$userId]); 

        if($hide_money->hide_money === 1){
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
