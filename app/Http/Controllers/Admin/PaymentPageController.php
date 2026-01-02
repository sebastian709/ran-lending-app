<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\loan_payment_approval_logs ;
use App\Models\loan\loan_payment ;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;

class PaymentPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $paymentCount = DB::table('loan_payments')->count();
        if ($paymentCount === 0) {
            return view('admin.payment.paymentempty');
        }else{
            return view('admin.payment.payment');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function get_pending_page_data(Request $request)
    {
        // dd($request->all());
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->input('search.value');

        // Total records
        $totalRecords = DB::table('loan_payments as lp')
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant') 
        ->count('lp.id');

        // Query
        $query = DB::table('loan_payments as lp')
        ->select([
            'lp.loan_application_id',
            DB::raw("CONCAT(u.firstname, ' ', u.lastname) as name"),
            DB::raw("lps.id test"),
            'lps.type',
            'lp.id'
        ])
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->where('lps.id', 1);

        $recordsFiltered = $query->count();

        // Pagination
        $pendings = $query->skip($start)->take($length)->get();
        // Add action column
        $data = $pendings->map(function ($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'type' => $pending->type,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary pending_view">View</button>',
            ];
        });

        

        // Response for DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function get_verified_page_data(Request $request)
    {
        // dd($request->all());
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->input('search.value');

        // Total records
        $totalRecords = DB::select("SELECT count(lp.id) total
       FROM loan_payments AS lp
        INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
        INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
        INNER JOIN users AS u ON u.id = la.loan_applicant
        WHERE lps.id = 3" );
        // Query
        $results = DB::select("SELECT 
                lp.loan_application_id,
                lp.payment_status_id,
                CONCAT(u.firstname, ' ', u.lastname) AS name,
                lps.type,
                lp.id,
                DATE(lp.created_at) AS date_paid,
                (SELECT lpal.id 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS logid,
                (SELECT DATE(lpal.created_at) 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS date_triggered
            FROM loan_payments AS lp
            INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
            INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
            INNER JOIN users AS u ON u.id = la.loan_applicant
            WHERE lps.id = 3
            ORDER BY lp.created_At DESC
            LIMIT ?, ?", [$start, $length]);

        // Pagination
        // Add action column
        $data = array_map(function($pending) {
            return [
               'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary verified_view">View</button>',
           ];
        }, $results);

        

        // Response for DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords[0]->total,
            'recordsFiltered' => $totalRecords[0]->total,
            'data' => $data,
        ]);
    }


    
    public function get_rejected_page_data(Request $request)
    {
        // dd($request->all());
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->input('search.value');

        // Total records
        $totalRecords = DB::select("SELECT count(lp.id) total
       FROM loan_payments AS lp
        INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
        INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
        INNER JOIN users AS u ON u.id = la.loan_applicant
        WHERE lps.id = 4" );
        // Query
        $results = DB::select("SELECT 
                lp.loan_application_id,
                lp.payment_status_id,
                CONCAT(u.firstname, ' ', u.lastname) AS name,
                lps.type,
                lp.id,
                DATE(lp.created_at) AS date_paid,
                (SELECT lpal.id 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS logid,
                (SELECT DATE(lpal.created_at) 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS date_triggered
            FROM loan_payments AS lp
            INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
            INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
            INNER JOIN users AS u ON u.id = la.loan_applicant
            WHERE lps.id = 4
            ORDER BY lp.created_at DESC
            LIMIT ?, ?", [$start, $length]);

        // Pagination
        // Add action column
        $data = array_map(function($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'logid' => $pending->logid,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary rejected_view">View</button>',
            ];
        }, $results);

        // Response for DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords[0]->total,
            'recordsFiltered' => $totalRecords[0]->total,
            'data' => $data,
        ]);
    }
    
    
    public function get_revision_page_data(Request $request)
    {
        // dd($request->all());
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->input('search.value');

         // Total records
        $totalRecords = DB::select("SELECT count(lp.id) total
       FROM loan_payments AS lp
        INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
        INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
        INNER JOIN users AS u ON u.id = la.loan_applicant
        WHERE lps.id = 5" );
        // Query
        $results = DB::select("SELECT 
                lp.loan_application_id,
                lp.payment_status_id,
                CONCAT(u.firstname, ' ', u.lastname) AS name,
                lps.type,
                lp.id,
                DATE(lp.created_at) AS date_paid,
                (SELECT lpal.id 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS logid,
                (SELECT DATE(lpal.created_at) 
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS date_triggered
            FROM loan_payments AS lp
            INNER JOIN loan_payment_statuses AS lps ON lps.id = lp.payment_status_id
            INNER JOIN loan_application AS la ON la.id = lp.loan_application_id
            INNER JOIN users AS u ON u.id = la.loan_applicant
            WHERE lps.id = 5
            LIMIT ?, ?", [$start, $length]);

        // Pagination
        // Add action column
        $data = array_map(function($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'logid' => $pending->logid,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary revision_view">View</button>',
            ];
        }, $results);

        // Response for DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords[0]->total,
            'recordsFiltered' => $totalRecords[0]->total,
            'data' => $data,
        ]);
    }
    

    public function get_pending_data(Request $request){

        $data = DB::selectOne("SELECT *
                            FROM loan_application AS la
                            JOIN users AS u ON u.id = la.loan_applicant
                            JOIN loan_tenure AS lt ON lt.loan_id = la.id
                            JOIN loan_tenure_interest AS lti ON lti.tenure_id = lt.id
                            WHERE la.id = ?
                    ", [$request->id]);

        $due = DB::selectOne("SELECT 
                        lt.date,
                        lt.count,
                        IF(lt.payment_status_id = 1 AND lti.payment_status_id = 1, lt.date, 0) AS payment_date
                    FROM loan_application AS la
                    JOIN loan_tenure AS lt 
                        ON lt.loan_id = la.id
                    JOIN loan_tenure_interest AS lti 
                        ON lti.tenure_id = lt.id
                    WHERE IF(lt.payment_status_id = 1 AND lti.payment_status_id = 1, lt.date, 0) != 0 and la.id = ?
                ",[$request->id]);

                $date = DB::selectOne("SELECT 
                        lt.id,
                        lt.count,
                        DATE_FORMAT(lt.date, '%M %D %Y') AS date,
                        MAX(llt.count) AS total_tenure,
                        SUM(IF(lt.payment_id = 0, lt.principal, 0)) AS principal,
                        SUM(IF(lti.payment_id = 0, lti.interest, 0)) AS interest,
                        SUM(IF(ltp.payment_id = 0, ltp.penalty, 0)) AS penalty,
                        SUM(
                            IF(lt.payment_id = 0, lt.principal, 0) + 
                            IF(lti.payment_id = 0 AND lti.payment_status_id != 4, lti.interest, 0) + 
                            IF(ltp.payment_id = 0, ltp.penalty, 0)
                        ) AS total_amount
                    FROM loan_tenure lt
                    INNER JOIN loan_tenure_interest lti ON lti.tenure_id = lt.id
                    LEFT JOIN loan_tenure_penalty ltp ON ltp.tenure_id = lt.id
                    LEFT JOIN loan_tenure llt ON llt.loan_id = lt.loan_id
                    WHERE lt.loan_id = ?
                    GROUP BY lt.id, lt.count, lt.date
                    HAVING total_amount > 0
                    ", [$request->id]);
    // dd('test');

        if (empty($date)) {
            $date = DB::selectOne("SELECT 
                            lt.id,
                            lt.count,
                            DATE_FORMAT(lt.date, '%M %D %Y') AS date,
                            MAX(llt.count) AS total_tenure,
                            SUM(IF(lt.payment_id = 0, lt.principal, 0)) AS principal,
                            SUM(IF(lti.payment_id = 0, lti.interest, 0)) AS interest,
                            SUM(IF(ltp.payment_id = 0, ltp.penalty, 0)) AS penalty,
                            SUM(
                                IF(lt.payment_id = 0, lt.principal, 0) + 
                                IF(lti.payment_id = 0 AND lti.payment_status_id != 4, lti.interest, 0) + 
                                IF(ltp.payment_id = 0, ltp.penalty, 0)
                            ) AS total_amount
                        FROM loan_tenure lt
                        INNER JOIN loan_tenure_interest lti ON lti.tenure_id = lt.id
                        LEFT JOIN loan_tenure_penalty ltp ON ltp.tenure_id = lt.id
                        LEFT JOIN loan_tenure llt ON llt.loan_id = lt.loan_id
                        WHERE lt.loan_id = ?
                        GROUP BY lt.id, lt.count, lt.date
                        order by lt.id desc
                    ", [$request->id]);
        }
        
        $total_interest = DB::selectOne("SELECT 
                        sum(interest) interest
                    FROM loan_tenure lt
                    INNER JOIN loan_tenure_interest lti ON lti.tenure_id = lt.id
                    WHERE lt.loan_id = ?", [$request->id]);
        

        $history = DB::select("SELECT ifnull(remarks,'N/A') remarks,lps.type,DATE_FORMAT(date(lp.created_at), '%M %D %Y') date,lp.payment_status_id
                from loan_payments lp
                inner join loan_payment_statuses lps on lps.id = lp.payment_status_id
                where lp.loan_application_id = ? 
                order by lp.id desc",[$request->id]);


        return response()->json([
            'success' => true,
            'data' => $data,
            'due' => $due,
            'date' => $date,
            'history' => $history,
            'total_interest' => $total_interest->interest,
        ]);

    }


    public function get_pending_data_two(Request $request){

        $data['behavior'] = DB::selectOne("SELECT lp.*,lpt.type payment_type,
                        DATE_FORMAT(lp.created_at, '%M %D %Y') paid_date,

                CASE
                        WHEN date(lp.created_at) <= if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN 'On time'
                        ELSE 'Late'
                    END AS payment_status,
                    CASE 
                        WHEN date(lp.created_at) < if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date), date(lp.created_at))
                        ELSE 0
                    END AS days_advance,
                    CASE
                        WHEN date(lp.created_at) > if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(date(lp.created_at), if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date))
                        ELSE 0
                    END AS days_late
                from loan_payments lp
                inner join loan_payment_types lpt on lpt.id = lp.payment_type_id
                left join loan_tenure lt on lt.payment_id = lp.id
                left join loan_tenure_interest lti on lti.payment_id = lp.id
                left join loan_tenure_penalty ltp on ltp.payment_id = lp.id
                
                where lp.id = ? limit 1",[$request->pay_id]);

        $data['payment'] = DB::select("SELECT 
                    DATE_FORMAT(DATE(lt.date), '%M %Y') AS tenure_date,
                    DATE(lt.updated_at) AS lt_paid_date,
                    DATE(lti.updated_at) AS lti_paid_date,
                    DATE(MAX(ltp.updated_at)) AS ltp_paid_date,

                    IF(lt.payment_id = ?, lt.principal, 0) AS paid_principal,
                    IF(lti.payment_id = ? and lti.payment_status_id != 4, lti.interest, 0) AS paid_interest,
                    IF(MAX(ltp.payment_id) = ?, SUM(ltp.penalty), 0) AS paid_penalty,

                    lt.principal,
                    lti.interest,
                    SUM(ltp.penalty) AS penalty
                FROM loan_tenure lt
                INNER JOIN loan_tenure_interest lti 
                    ON lti.tenure_id = lt.id
                LEFT JOIN loan_tenure_penalty ltp 
                    ON ltp.tenure_id = lt.id
                GROUP BY 
                    lt.id,
                    lt.date,
                    lt.updated_at,
                    lti.updated_at,
                    lt.payment_id,
                    lti.payment_id,
                    lt.principal,
                    lti.payment_status_id,
                    lti.interest
                ",[$request->pay_id,$request->pay_id,$request->pay_id]);

        // dd($data['payment']);

        foreach ($data['payment'] as $key => $row) {
            if ($row->paid_principal === 0.0 && $row->paid_interest === 0.0 && $row->paid_penalty === 0.0) {
                unset($data['payment'][$key]);
            }
        }
        // dd($data['payment']);
        $data['total_principal'] = 0 ;
        $data['total_interest'] = 0 ;
        $data['total_penalty'] = 0 ;

        $data['total_principal'] = array_sum(array_column($data['payment'], 'paid_principal'));
        $data['total_interest']  = array_sum(array_column($data['payment'], 'paid_interest'));
        $data['total_penalty']  = array_sum(array_column($data['payment'], 'paid_penalty'));

        $data['totalpaid'] = $data['total_principal'] + $data['total_interest'] + $data['total_penalty'];

        $data['total_principal'] = number_format($data['total_principal'], 2);
        $data['total_interest'] = number_format($data['total_interest'], 2);
        $data['total_penalty'] = number_format($data['total_penalty'], 2);
        
        $data['raw_principal'] = 0 ;
        $data['raw_interest'] = 0 ;
        $data['raw_penalty'] = 0 ;

        $data['principal'] = array_sum(array_column($data['payment'], 'principal'));
        $data['interest']  = array_sum(array_column($data['payment'], 'interest'));
        $data['penalty']  = array_sum(array_column($data['payment'], 'penalty'));

        $data['raw_principal'] = number_format($data['raw_principal'], 2);
        $data['raw_interest'] = number_format($data['raw_interest'], 2);
        $data['raw_penalty'] = number_format($data['raw_penalty'], 2);

        $data['totalrawpaid'] = $data['principal'] + $data['interest'] + $data['penalty'];





        $first = reset($data['payment']);   // first object
        $last  = end($data['payment']);     // last object

        $data['from'] = $first->tenure_date;
        $data['to']   = $last->tenure_date;







        return response()->json([
            'success' => true,
            'data' => $data,
        ]);

    }

        public function verify(Request $request){
            
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.key'));
        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

        // dd($request->imageFile);
        $data = $request->id;
        $value = $request->value;
        
        $loan_info = Loan_payment::where('loan_payments.id', (int)$data)
            ->join('loan_application', 'loan_application.id', '=', 'loan_payments.loan_application_id')
            ->join('users', 'users.id', '=', 'loan_application.loan_applicant')
            ->first();

        //REJECT REJECT========================================================================
        if ($request->value == 4) {
            $path = null;

            if ($request->hasFile('imageFile')) {

                $file = $request->file('imageFile');
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Process image
                $image = Image::make($file)
                    ->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                // Direct physical path: public/storage/uploads/appeal_attachments
                $destination = public_path('storage/uploads/appeal_attachments/' . $filename);

                // Ensure folder exists
                if (!file_exists(dirname($destination))) {
                    mkdir(dirname($destination), 0777, true);
                }

                // Save directly to public/storage
                $image->save($destination, 80);

                // dd('uploads/appeal_attachments/' . $filename);
                $path = 'uploads/appeal_attachments/' . $filename;
            }

            loan_payment_approval_logs::insertGetId([
                'loan_payment_id'  => (int)$data,
                'action'  => (int)$value, 
                'attachment'  => $path, 
                'reason'  => $request->reason, 
                'actual_amount'  => $request->received, 
                'remarks'  => $request->remarks, 
                'created_at'  => now(),
                'added_by'  => auth()->id(),
            ]);

            //UPDATE DATA
            DB::update('UPDATE loan_payments SET payment_status_id = ? WHERE id = ?', [4, $data]);
        }
        //APPROVE APPROVE-=======================================================================
        if ($request->value == 3) {

            $path = null;

            if ($request->hasFile('imageFile')) {
                $file = $request->file('imageFile');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $image = Image::make($file)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio(); 
                    $constraint->upsize();
                })
                ->save(storage_path('app/public/upload_files/' . $filename), 80);
                $path = 'approved_images/' . $filename;
                Storage::disk('public')->put($path, (string) $image);

            }
            loan_payment_approval_logs::insertGetId([
                'attachment'  => $path, 
                'loan_payment_id'  => (int)$data,
                'action'  => (int)$value, 
                'created_at'  => now(),
                'added_by'  => auth()->id(),
            ]);
            
            //UPDATE DATA
            DB::update('UPDATE loan_payments SET payment_status_id = ? WHERE id = ?', [3, $data]);

            //CHECK IF FULLYPAID
            $checker = DB::select("SELECT 
                        lt.id,
                        lt.count,
                        DATE_FORMAT(lt.date, '%M %D %Y') date,
                        MAX(llt.count) as total_tenure,
                        IF(lt.payment_id = 0, lt.principal, 0) principal,
                        IF(lti.payment_id = 0, lti.interest, 0) interest,
                        IF(ltp.payment_id = 0, sum(ltp.penalty), 0) penalty,
                        SUM(IF(lt.payment_id = 0, lt.principal, 0) + IF(lti.payment_id = 0 AND lti.payment_status_id != 5, lti.interest, 0)) + IF(ltp.payment_id = 0, sum(ltp.penalty), 0)  total_amount
                    FROM loan_tenure lt
                    INNER JOIN loan_tenure_interest lti ON lti.tenure_id = lt.id
                    LEFT JOIN loan_tenure_penalty ltp ON ltp.tenure_id = lt.id
                    LEFT JOIN loan_tenure llt ON llt.loan_id = lt.loan_id
                    WHERE lt.loan_id = ?
                    GROUP BY lt.id, lt.count, lt.date, lt.payment_id, lti.payment_id,ltp.payment_id, lt.principal, lti.interest, lti.payment_status_id
                    HAVING total_amount > 0",[$data]);

            //IF EMPTZY MEANS FULLY PAID NOW
            if (empty($checker)) {
                
                $updateData = [
                    'updated_at' => now(),
                    'loan_status' => 7,
                ];
                $loan_application = DB::selectOne('SELECT loan_application_id FROM loan_payments where id = ?',[$data]);
                
                DB::update('UPDATE loan_application SET updated_at = ?, loan_status = ? WHERE id = ?',[now(), 7, $loan_application->loan_application_id]);
                
                $is_eligible = DB::selectOne('SELECT la.red_flag 
                                            from loan_application la
                                            inner join loan_payments lp on lp.loan_application_id = la.id
                                            where lp.id = ?',[$data]);
                
                $content = "
                        Great job! You’ve successfully completed your loan payment
                        Thank you for your commitment and trust in our service.";

                if ($is_eligible->red_flag != 1) {
                    $content .= "<br>You’re now eligible to apply for a new loan anytime.";
                }


                $emailObj = new SendSmtpEmail([
                    'subject' => 'Congratulations! Your Loan Has Been Fully Paid',
                    'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
                    'to' => [['email' => $loan_info->email]],
                    'htmlContent' => $content,
                ]);

                $apiInstance->sendTransacEmail($emailObj);

                //================================================================

                return response()->json(1);
            }






        }
        //Revision APPROVE-=======================================================================
        if ($request->value == 5) {
            loan_payment_approval_logs::insertGetId([
                'loan_payment_id'  => (int)$data,
                'action'  => (int)$value, 
                'reason'  => $request->reasonRevision, 
                'actual_amount'  => $request->received, 
                'instruction'  => $request->instructions, 
                'remarks'  => $request->revisionRemarks, 
                'created_at'  => now(),
                'added_by'  => auth()->id(),
            ]);
               //UPDATE DATA
            DB::update('UPDATE loan_payments SET payment_status_id = ? WHERE id = ?', [5, $data]);
        }
        
        $Loan_payment = Loan_payment::where('id', (int)$data)
                                        ->update(['payment_status_id' => (int)$value]);
        
        //================================================================

        if((int)$value == 5){ //Revision
            $content = "We reviewed your payment and need some adjustments before it can be verified.";
        }
        if((int)$value == 4){ //REJECTED
            $content = "Your payment transaction made last ".$loan_info->created_at." for [Month coverage] is marked as failed/for revision.";
        }
        if((int)$value == 3){//APPROVED
            $content = "Your payment is now confirmed! Thank you for paying ".$loan_info->amount_sent;
        }

        $emailObj = new SendSmtpEmail([
            'subject' => 'Payment Confirmation',
            'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
            'to' => [['email' => $loan_info->email]],
            'htmlContent' => $content,
        ]);

        $apiInstance->sendTransacEmail($emailObj);

        //================================================================

        return response()->json(1);

    }

    public function get_verified_page_data_view_more(Request $request){
        // dd($request->pay_id);
        
        $data['behavior'] = DB::select("SELECT lp.*,lpt.type payment_type,
                CASE
                        WHEN date(lp.created_at) <= if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN 'On time'
                        ELSE 'Late'
                    END AS payment_status,
                    CASE 
                        WHEN date(lp.created_at) < if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date), date(lp.created_at))
                        ELSE 0
                    END AS days_advance,
                    CASE
                        WHEN date(lp.created_at) > if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(date(lp.created_at), if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date))
                        ELSE 0
                    END AS days_late
                from loan_payments lp
                inner join loan_payment_types lpt on lpt.id = lp.payment_type_id
                left join loan_tenure lt on lt.payment_id = lp.id
                left join loan_tenure_interest lti on lti.payment_id = lp.id
                left join loan_tenure_penalty ltp on ltp.payment_id = lp.id
                
                where lp.id = ? limit 1",[$request->pay_id]);

        $data['payment'] = DB::select("SELECT 
                    DATE_FORMAT(date(lt.date), '%M %Y') tenure_date,
                    date(lt.updated_at) lt_paid_date,
                    date(lti.updated_at) lti_paid_date,
                    date(ltp.updated_at) ltp_paid_date,
                    if(lt.payment_id = ?,lt.principal,0) paid_principal,
                    if(lti.payment_id = ?,lti.interest,0) paid_interest,
                    if(ltp.payment_id = ?,ltp.penalty,0) paid_penalty,
                    lt.principal,
                    lti.interest,
                    ltp.penalty
                    from loan_tenure lt
                    inner join loan_tenure_interest lti on lti.tenure_id = lt.id
                    left join loan_tenure_penalty ltp on ltp.tenure_id = lt.id",[$request->pay_id,$request->pay_id,$request->pay_id]);

        // dd($data);

        foreach ($data['payment'] as $key => $row) {
            if ($row->paid_principal === 0.0 && $row->paid_interest === 0.0 && $row->paid_penalty === 0.0) {
                unset($data['payment'][$key]);
            }
        }

        $data['total_principal'] = 0 ;
        $data['total_interest'] = 0 ;
        $data['total_penalty'] = 0 ;

        $data['total_principal'] = array_sum(array_column($data['payment'], 'paid_principal'));
        $data['total_interest']  = array_sum(array_column($data['payment'], 'paid_interest'));
        $data['total_penalty']  = array_sum(array_column($data['payment'], 'paid_penalty'));

        $data['total_principal'] = number_format($data['total_principal'], 2);
        $data['total_interest'] = number_format($data['total_interest'], 2);
        $data['total_penalty'] = number_format($data['total_penalty'], 2);

        $data['totalpaid'] = $data['total_principal'] + $data['total_interest'] + $data['total_penalty'];

        $data['raw_principal'] = 0 ;
        $data['raw_interest'] = 0 ;
        $data['raw_penalty'] = 0 ;

        $data['principal'] = array_sum(array_column($data['payment'], 'principal'));
        $data['interest']  = array_sum(array_column($data['payment'], 'interest'));
        $data['penalty']  = array_sum(array_column($data['payment'], 'penalty'));

        $data['raw_principal'] = number_format($data['raw_principal'], 2);
        $data['raw_interest'] = number_format($data['raw_interest'], 2);
        $data['raw_penalty'] = number_format($data['raw_penalty'], 2);

        $data['totalrawpaid'] = $data['principal'] + $data['interest'] + $data['penalty'];





        $first = reset($data['payment']);   // first object
        $last  = end($data['payment']);     // last object

        $data['from'] = $first->tenure_date;
        $data['to']   = $last->tenure_date;


        // dd($data['payment']);
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function get_rejected_page_data_view_more(Request $request){
        // dd($request->pay_id);
        
        $data['behavior'] = DB::select("SELECT lp.*,lpt.type payment_type,
                (SELECT lpal.reason
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS reason,
                (SELECT ifnull(lpal.remarks,'N/A')
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS remarks,
                (SELECT lpal.actual_amount
                FROM loan_payment_approval_logs lpal 
                WHERE lpal.loan_payment_id = lp.id 
                ORDER BY id DESC LIMIT 1) AS actual_amount,
                CASE
                        WHEN date(lp.created_at) <= if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN 'On time'
                        ELSE 'Late'
                    END AS payment_status,
                    CASE 
                        WHEN date(lp.created_at) < if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date), date(lp.created_at))
                        ELSE 0
                    END AS days_advance,
                    CASE
                        WHEN date(lp.created_at) > if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date) THEN DATEDIFF(date(lp.created_at), if(lt.date is null ,(select date from loan_tenure where id = lti.tenure_id) , lt.date))
                        ELSE 0
                    END AS days_late
                from loan_payments lp
                inner join loan_payment_types lpt on lpt.id = lp.payment_type_id
                left join loan_tenure lt on lt.payment_id = lp.id
                left join loan_tenure_interest lti on lti.payment_id = lp.id
                left join loan_tenure_penalty ltp on ltp.payment_id = lp.id
                
                where lp.id = ? limit 1",[$request->pay_id]);


            $data['payment'] = DB::select("SELECT 
                    DATE_FORMAT(date(lt.date), '%M %Y') tenure_date,
                    date(lt.updated_at) lt_paid_date,
                    date(lti.updated_at) lti_paid_date,
                    date(ltp.updated_at) ltp_paid_date,
                    if(lt.payment_id = ?,lt.principal,0) paid_principal,
                    if(lti.payment_id = ?,lti.interest,0) paid_interest,
                    if(ltp.payment_id = ?,ltp.penalty,0) paid_penalty,
                    lt.principal,
                    lti.interest,
                    ltp.penalty
                    from loan_tenure lt
                    inner join loan_tenure_interest lti on lti.tenure_id = lt.id
                    left join loan_tenure_penalty ltp on ltp.tenure_id = lt.id",[$request->pay_id,$request->pay_id,$request->pay_id]);

        // dd($data);

        foreach ($data['payment'] as $key => $row) {
            if ($row->paid_principal === 0.0 && $row->paid_interest === 0.0 && $row->paid_penalty === 0.0) {
                unset($data['payment'][$key]);
            }
        }

        $data['total_principal'] = 0 ;
        $data['total_interest'] = 0 ;
        $data['total_penalty'] = 0 ;

        $data['total_principal'] = array_sum(array_column($data['payment'], 'paid_principal'));
        $data['total_interest']  = array_sum(array_column($data['payment'], 'paid_interest'));
        $data['total_penalty']  = array_sum(array_column($data['payment'], 'paid_penalty'));

        $data['total_principal'] = number_format($data['total_principal'], 2);
        $data['total_interest'] = number_format($data['total_interest'], 2);
        $data['total_penalty'] = number_format($data['total_penalty'], 2);

        $data['totalpaid'] = $data['total_principal'] + $data['total_interest'] + $data['total_penalty'];

        $data['raw_principal'] = 0 ;
        $data['raw_interest'] = 0 ;
        $data['raw_penalty'] = 0 ;

        $data['principal'] = array_sum(array_column($data['payment'], 'principal'));
        $data['interest']  = array_sum(array_column($data['payment'], 'interest'));
        $data['penalty']  = array_sum(array_column($data['payment'], 'penalty'));

        $data['raw_principal'] = number_format($data['raw_principal'], 2);
        $data['raw_interest'] = number_format($data['raw_interest'], 2);
        $data['raw_penalty'] = number_format($data['raw_penalty'], 2);

        $data['totalrawpaid'] = $data['principal'] + $data['interest'] + $data['penalty'];

        $first = reset($data['payment']);   // first object
        $last  = end($data['payment']);     // last object

        $data['from'] = $first->tenure_date;
        $data['to']   = $last->tenure_date;


        // dd($data['payment']);
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function checkappeal(Request $request){
        // dd(auth()->id());

        // -- 1	For Verification
        // -- 2	For Correction
        // -- 3	Verified
        // -- 4	Rejected
        // -- 5	For Revision
        // -- 6	For Apppeal
        date_default_timezone_set('Asia/Manila');
        
        $data['loanid'] = DB::selectOne("SELECT id from loan_application 
                        where loan_applicant = ?
                        order by id desc limit 1",[auth()->id()]);
        
        $data['paymentid'] = DB::selectOne("SELECT id,payment_status_id 
                        from loan_payments 
                        where loan_application_id = ?
                        and cancelled_approved_date is null
                        order by id desc limit 1",[$data['loanid']->id]);

        if (empty($data['paymentid'])) {
            return response()->json([
                'success' => false,
                'data' => '',
                'image' =>  ''
            ]);
        }

        $data['paymentlog'] = DB::selectOne("SELECT * from loan_payment_approval_logs 
                        where loan_payment_id = ? 
                        and declined_accepted is null
                        order by id desc limit 1",[$data['paymentid']->id]);
        $image = null;
        if ($data['paymentlog'] != null) {
            $image = asset('storage/' . $data['paymentlog']->attachment);
        }      
        // dd($data);
    
        return response()->json([
            'success' => true,
            'data' => $data,
            'image' =>  $image
        ]);

    }


        public function appealuser(Request $request){
        // dd($request->all(), $request->file());

        // -- 1	For Verification
        // -- 2	For Correction
        // -- 3	Verified
        // -- 4	Rejected
        // -- 5	For Revision
        // -- 6	For Apppeal
        $data['email'] = DB::selectOne("SELECT email from users where id = ?",[auth()->id()]);
                        
        $data['loanid'] = DB::selectOne("SELECT id from loan_application 
                        where loan_applicant = ?
                        order by id desc limit 1",[auth()->id()]);
        
        $data['paymentid'] = DB::selectOne("SELECT id,payment_status_id 
                        from loan_payments 
                        where loan_application_id = ?
                        order by id desc limit 1",[$data['loanid']->id]);

        $data['paymentlog'] = DB::selectOne("SELECT * from loan_payment_approval_logs 
                        where loan_payment_id = ? 
                        order by id desc limit 1",[$data['paymentid']->id]);

        $updateData = [
            'loan_id' => $data['loanid']->id,
            'payment_id' => $data['paymentid']->id,
            'reason' => $request->reason,
            'date_of_appeal' => now(),
            'uploaded_proof' => null, // default
        ];

        // File upload handling
        $folderMap = [
            'upload' => ['folder' => 'loan_appeal', 'db_field' => 'uploaded_proof'],
        ];

        foreach ($folderMap as $requestField => $info) {
            if ($request->hasFile($requestField)) {
                $file = $request->file($requestField);

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $directory = public_path("storage/uploads/{$info['folder']}");

                if (!file_exists($directory)) {
                    mkdir($directory, 0775, true);
                }

                $file->move($directory, $filename);
                $updateData[$info['db_field']] = "uploads/{$info['folder']}/{$filename}";
            }
        }

        // Insert directly into loan_appeal table
        DB::table('loan_appeal')->insert($updateData);

        //UPDATE DATA
        DB::update('UPDATE loan_payments SET payment_status_id = 6 WHERE id = ?', [$data['paymentid']->id]);
        // dd($data);

        $config = Configuration::getDefaultConfiguration()
        ->setApiKey('api-key', config('services.brevo.key'));
        
        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

        $content = "Appeal Has been Sent to the Admin . Please Wait for the Verdict.";

        $emailObj = new SendSmtpEmail([
            'subject' => 'Appeal Sent Confirmation',
            'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
            'to' => [['email' => $data['email']->email]],
            'htmlContent' => $content,
        ]);
        // dd($emailObj);
        $apiInstance->sendTransacEmail($emailObj);

        return response()->json([
            'status' => 'success',
            'message' => 'Appeal submitted successfully',
            'name' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
        ]);

    }
}
