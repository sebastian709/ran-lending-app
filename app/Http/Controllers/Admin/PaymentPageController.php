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
        return view('admin.payment.payment');
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
        ->join('users as u', 'u.id', '=', 'lp.loan_application_id') // ⚠️ double-check this join
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
        ->join('users as u', 'u.id', '=', 'lp.loan_application_id') // <-- check this join!
        ->where('lps.id', (int)$request->id);

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
        $totalRecords = DB::table('loan_payments as lp')
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 3)
        ->count('lp.id');

        // Query
        $query = DB::table('loan_payments as lp')
        ->select([
            'lp.loan_application_id',
            'lp.payment_status_id',
            DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS name"),
            'lps.type',
            'lp.id',
            DB::raw('DATE(lp.created_at) AS date_paid'),
            DB::raw('DATE(lpal.created_at) AS date_triggered'),
        ])
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 3);

        $recordsFiltered = $query->count();

        // Pagination
        $pendings = $query->skip($start)->take($length)->get();
        // Add action column
        $data = $pendings->map(function ($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary verified_view">View</button>',
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


    
    public function get_rejected_page_data(Request $request)
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
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 4)
        ->count('lp.id');

        // Query
        $query = DB::table('loan_payments as lp')
        ->select([
            'lp.loan_application_id',
            'lp.payment_status_id',
            DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS name"),
            'lps.type',
            'lp.id',
            DB::raw('DATE(lp.created_at) AS date_paid'),
            DB::raw('DATE(lpal.created_at) AS date_triggered'),
            DB::raw('lpal.id AS logid'),
        ])
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 4);

        $recordsFiltered = $query->count();

        // Pagination
        $pendings = $query->skip($start)->take($length)->get();
        // Add action column
        $data = $pendings->map(function ($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'logid' => $pending->logid,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary rejected_view">View</button>',
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
    
    
    public function get_revision_page_data(Request $request)
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
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 5)
        ->count('lp.id');

        // Query
        $query = DB::table('loan_payments as lp')
        ->select([
            'lp.loan_application_id',
            'lp.payment_status_id',
            DB::raw("CONCAT(u.firstname, ' ', u.lastname) AS name"),
            'lps.type',
            'lp.id',
            DB::raw('DATE(lp.created_at) AS date_paid'),
            DB::raw('DATE(lpal.created_at) AS date_triggered'),
            DB::raw('lpal.id AS logid'),
        ])
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'la.loan_applicant')
        ->join('loan_payment_approval_logs as lpal', 'lpal.loan_payment_id', '=', 'lp.id')
        ->where('lps.id', 5);

        $recordsFiltered = $query->count();

        // Pagination
        $pendings = $query->skip($start)->take($length)->get();
        // Add action column
        $data = $pendings->map(function ($pending) {
            return [
                'loan_application_id' => $pending->loan_application_id,
                'name' => $pending->name,
                'date_paid' => $pending->date_paid,
                'date_triggered' => $pending->date_triggered,
                'logid' => $pending->logid,
                'action' => '<button type="button" data-id="'.$pending->loan_application_id.'" data-pay_id="'.$pending->id.'" class="btn sm-btn btn-primary revision_view">View</button>',
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
    

    public function get_pending_data(Request $request){
        // dd($request->id);

        $data = DB::table('loan_application as la')
            ->join('users as u', 'u.id', '=', 'la.loan_applicant')
            ->join('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->join('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->where('la.id', $request->id)
            ->select('*')
            ->first();

        $due = DB::table('loan_application as la')
            ->join('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->join('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->select(
                'lt.date',
                'lt.count',
                DB::raw("IF(lt.payment_status_id = 1 AND lti.payment_status_id = 1, lt.date, 0) as payment_date")
            )
            ->whereRaw("IF(lt.payment_status_id = 1 AND lti.payment_status_id = 1, lt.date, 0) != 0")
            ->first();
        
        

        return response()->json([
            'success' => true,
            'data' => $data,
            'due' => $due
        ]);

    }


    public function get_pending_data_two(Request $request){
        
        $data = DB::table('loan_payments as lp')
            ->select('lp.*', 'lpt.type')
            ->join('loan_payment_types as lpt', 'lpt.id', '=', 'lp.payment_type_id')
            ->where('lp.id', $request->pay_id)
            ->first();
        $data->loan_tenure = DB::table('loan_tenure as lt')
            ->where('lt.payment_id', $data->id)
            ->get();
        
        $data->loan_tenure_interest = DB::table('loan_tenure_interest as lti')
            ->where('lti.payment_id', $data->id)
            ->get();
        
        $combined = ($data->loan_tenure)->merge($data->loan_tenure_interest);

        $tenureIds = $combined->pluck('tenure_id')->unique()->toArray();
        // dd($tenureIds);
        $data->combined = DB::table('loan_tenure')
            ->select('date')
            ->whereIn('id', $tenureIds)
            ->get();

        $data->total_balance = DB::table('loan_tenure as lt')
            ->join('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->whereIn('lt.id', $tenureIds)
            ->selectRaw('SUM( IF(lt.payment_id NOT IN ('.$request->pay_id.'), principal, 0) + IF(lti.payment_id NOT IN ('.$request->pay_id.'), interest, 0) ) as total')
            ->value('total');
        // dd($data->balance);
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);

    }

        public function verify(Request $request){
            
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
                $image = Image::make($file)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio(); 
                    $constraint->upsize();
                })
                ->save(storage_path('app/public/upload_files/' . $filename), 80);
                $path = 'rejected_images/' . $filename;
                Storage::disk('public')->put($path, (string) $image);

            }

            loan_payment_approval_logs::insertGetId([
                'loan_payment_id'  => (int)$data,
                'action'  => (int)$value, 
                'attachment'  => $path, 
                'reason'  => $request->reason, 
                'remarks'  => $request->remarks, 
                'created_at'  => now(),
                'added_by'  => auth()->id(),
            ]);

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
        }
        //Revision APPROVE-=======================================================================
        if ($request->value == 5) {
            loan_payment_approval_logs::insertGetId([
                'loan_payment_id'  => (int)$data,
                'action'  => (int)$value, 
                'reason'  => $request->reasonRevision, 
                'instruction'  => $request->instructions, 
                'remarks'  => $request->revisionRemarks, 
                'created_at'  => now(),
                'added_by'  => auth()->id(),
            ]);
        }
        
        $Loan_payment = Loan_payment::where('id', (int)$data)
                                        ->update(['payment_status_id' => (int)$value]);
        
        //================================================================
        $config = Configuration::getDefaultConfiguration()
        ->setApiKey('api-key', env('BREVO_API_KEY'));
        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

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


}
