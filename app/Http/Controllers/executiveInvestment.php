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
    public function index()
    {   
          return view('admin.pages.executive.executive');
    }

    public function add_investment(Request $request)
    {   
        $userId = auth()->id();

          DB::table('executive_account_balance')->insert([
            'amount' => $request->amount,
            'category' => $request->category,
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
    public function pull_data(Request $request)
    {
        $data['lending_fund'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where category = 1'); //lending fund
        $data['shared_fund'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where category = 2'); //shared fund

        $data['my_investment'] = DB::selectOne('SELECT sum(amount) amount from executive_account_balance where created_by = 3'); //total Investment
        
        $data['fund_management'] = DB::select("SELECT eab.*,concat(firstname,' ',lastname) name,
                                    if(category = 1,'Lending Fund','Shared Fund') categories,
                                    DATE_FORMAT(eab.created_at, '%b %d, %Y, %h:%i %p') readable_date
                                    from executive_account_balance eab
                                    inner join users u on u.id = eab.created_by order by eab.id desc"); //Fund Management


        // dd($data);
        return response()->json([
            'data' => $data
        ]);
    }
}
