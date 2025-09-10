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
        $admins = DB::table('users')
                ->select('id', DB::raw("CONCAT(firstname, ' ', lastname) as full_name"))
                ->where('is_admin', 1)
                ->where('is_super_admin', 0)
                ->get();

        return view('admin.pages.loanrequest.index', compact('admins'));
    }

    public function getLoanRequests(Request $request)
    {

        $loanStatus = $request->get('loan_status');

        $loanApplication = DB::table('loan_application')
            ->join('users', 'loan_application.loan_applicant', '=', 'users.id')
            ->join('loan_status', 'loan_application.loan_status', '=', 'loan_status.id')
            ->select(
                'loan_application.id',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as loan_applicant"),
                'loan_application.loan_amount',
                'loan_application.loan_tenure',
                'loan_application.interest_rate',
                DB::raw("IFNULL(loan_application.purpose_of_loan, 'None') as purpose_of_loan"),
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                'loan_application.referral',
                'loan_application.loan_status as loan_status',
                'loan_status.loan_status as loan_status_name'
            );

            if (!empty($loanStatus)) {
                $loanApplication->where('loan_application.loan_status', $loanStatus);
            }

            return response()->json($loanApplication->get());
    }

    public function getBorrowersApplication(Request $request){
        $loan_id = $request->loan_id;

        $loanApplication = DB::table('loan_application')
            ->join('users', 'loan_application.loan_applicant', '=', 'users.id')
            ->join('loan_status', 'loan_application.loan_status', '=', 'loan_status.id')
            // ->leftjoin('referral_source', 'referral_source.id', '=', 'users.referral_source_id')
            ->select(
                'loan_application.id',
                'users.id as user_id',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as loan_applicant"),
                'loan_application.loan_amount',
                'loan_application.loan_tenure',
                'loan_application.interest_rate',
                DB::raw("DATE_FORMAT(loan_application.created_at, '%b %d, %Y') as created_at"),
                DB::raw("DATE_FORMAT(loan_application.updated_at, '%b %d, %Y') as updated_at"),
                DB::raw("IFNULL(loan_application.referral, 'None') as referral"),
                // 'loan_application.referral',
                'loan_application.loan_status as loan_status',
                'loan_status.loan_status as loan_status_name',
                DB::raw("IFNULL(loan_application.purpose_of_loan, 'None') as purpose_of_loan"),
                // 'referral_source.name as referral',
                DB::raw("CONCAT('" . asset('storage') . "/', loan_application.payslip_img) as payslip_img"),
                DB::raw("CONCAT('" . asset('storage') . "/', loan_application.billing_statement_img) as billing_statement_img"),
                DB::raw("CONCAT('" . asset('storage') . "/', loan_application.upload_qr_code_img) as upload_qr_code_img"),
                DB::raw("CONCAT('" . asset('storage') . "/', loan_application.government_id_img) as government_id_img"),
                DB::raw("(SELECT COUNT(*) FROM loan_application WHERE loan_applicant = users.id) as loan_taken"),
                DB::raw("FORMAT((SELECT SUM(loan_amount) FROM loan_application WHERE loan_applicant = users.id), 2) as total_loan_amount"),
                DB::raw("(SELECT DATE_FORMAT(MIN(created_at), '%b %d, %Y') FROM loan_application WHERE loan_applicant = users.id) as first_loan_date"),
                DB::raw("(SELECT DATE_FORMAT(MAX(created_at), '%b %d, %Y') FROM loan_application WHERE loan_applicant = users.id) as last_loan_date")
            )
            ->where('loan_application.id', $loan_id)
            ->first();
            
           $logs = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->where('activity_logs.loan_id', $loan_id)
            ->orderBy('activity_logs.created_at', 'desc')
            ->select(
                'activity_logs.*',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as user_name")
            )
            ->get();

            $approved_admins = DB::table('loan_application')
                ->select('approved_by_admins')
                ->where('id', $loan_id)
                ->value('approved_by_admins');

            $approved_admins_array = explode(',', $approved_admins);
            
            $disapproved_admins = DB::table('loan_application')
                ->select('disapproved_by_admins')
                ->where('id', $loan_id)
                ->value('disapproved_by_admins');

            $disapproved_admins_array = explode(',', $disapproved_admins);
            
            $loan_status =  DB::table('loan_status')
                ->select('id','loan_status')
                ->where('status', 1)
                ->get();
            
            $user = auth()->user();
            $loan_request_access = DB::table('admin_loan_request_access')
                ->where('user_id', $user->id)
                ->get();

            return response()->json([
                "data" => $loanApplication,
                "logs" => $logs,
                "approved_by" => $approved_admins_array,
                "disapproved_by" => $disapproved_admins_array,
                "loan_request_access" => $loan_request_access,
                "loan_status" => $loan_status
            ]);
    }

    public function approve(Request $request, $id)
    {
        $loan = DB::table('loan_application')->where('id', $id)->first();

        $approved = $loan->approved_by_admins ? explode(',', $loan->approved_by_admins) : [];
        $disapproved = $loan->disapproved_by_admins ? explode(',', $loan->disapproved_by_admins) : [];

        // Add admin to approved if not present
        if (!in_array($request->admin_id, $approved)) {
            $approved[] = $request->admin_id;
        }

        // Remove admin from disapproved if present
        $disapproved = array_filter($disapproved, fn($id) => $id != $request->admin_id);

        // Count total active admins (not super admins)
        $admin_count = DB::table('users')
            ->where('status', 1)
            ->where('is_super_admin', 0)
            ->where('is_admin', 1)
            ->count();

        //Remove duplicates from approved list
        $approved = array_unique($approved);

        // Prepare update data
        $updateData = [
            'approved_by_admins' => implode(',', $approved),
            'disapproved_by_admins' => implode(',', $disapproved),
        ];

        // If all admins approved → set loan_status = 3
        if (count($approved) == $admin_count) {
            $updateData['loan_status'] = 2;
        }

        //Save updates
        DB::table('loan_application')->where('id', $id)->update($updateData);

        ActivityLogger::log('Approved', 'Approved Loan Request', $id);

        return response()->json([
            'success' => true,
            'approved_by_admins' => implode(',', $approved),
            'disapproved_by_admins' => implode(',', $disapproved),
        ]);
    }


    public function reject(Request $request, $id)
    {
        $loan = DB::table('loan_application')->where('id', $id)->first();

        $approved = $loan->approved_by_admins ? explode(',', $loan->approved_by_admins) : [];
        $disapproved = $loan->disapproved_by_admins ? explode(',', $loan->disapproved_by_admins) : [];

        // Add admin to disapproved if not present
        if (!in_array($request->admin_id, $disapproved)) {
            $disapproved[] = $request->admin_id;
        }

        // Remove admin from approved if present
        $approved = array_filter($approved, fn($adminId) => $adminId != $request->admin_id);

        // Update DB
        DB::table('loan_application')->where('id', $id)->update([
            'approved_by_admins' => implode(',', $approved),
            'disapproved_by_admins' => implode(',', $disapproved),
        ]);

        // Count total admins
        $admin_count = DB::table('users')
            ->where('status', 1)
            ->where('is_super_admin', 0)
            ->where('is_admin', 1)
            ->count();

        // If all admins disapproved → status = 3
        if (count($disapproved) === $admin_count) {
            DB::table('loan_application')->where('id', $id)->update([
                'loan_status' => 6
            ]);
        }else{
            DB::table('loan_application')->where('id', $id)->update([
                'loan_status' => 1
            ]);
        }

        ActivityLogger::log('Reject', 'Rejected Loan Request', $id);

        return response()->json([
            'success' => true,
            'approved_by_admins' => implode(',', $approved),
            'disapproved_by_admins' => implode(',', $disapproved),
        ]);
    }
    public function rejectField(Request $request, $id)
    {
        DB::table('loan_application')
            ->where('id', $id)
            ->update(['loan_status' => 3]);

        $fieldId = $request->input('field_id');
        $data = [
            'loan_id' => $id,
            'rejected_date' => now(),
        ];

        switch ($fieldId) {
            case 'rejectProofofIncome':
                $data['payslip_img'] = 1;
                ActivityLogger::log('Reject Field', 'Proof of income was rejected', $id);
                break;

            case 'rejectAmount':
                $data['loan_amount'] = 1;
                if ($request->has('suggested_amount')) {
                    $data['amount_suggested'] = $request->input('suggested_amount');
                }

                if ($request->has('amount_remarks')) {
                    $data['amount_remarks'] = $request->input('amount_remarks');
                }
                
                ActivityLogger::log('Reject Field', 'Loan amount was rejected. Suggested: '.$request->input('suggested_amount'). ' Remarks: ' . $request->input('amount_remarks'), $id);
                break;

            case 'rejectQRcode':
                $data['upload_qr_code_img'] = 1;
                ActivityLogger::log('Reject Field', 'QR Code was rejected', $id);
                break;

            case 'rejectGovernmentID':
                $data['government_id_img'] = 1;
                ActivityLogger::log('Reject Field', 'Government ID was rejected', $id);
                break;

            case 'rejectBilling':
                $data['billing_statement_img'] = 1;
                ActivityLogger::log('Reject Field', 'Billing statement was rejected', $id);
                break;
        }

        DB::table('loan_rejected_fields')->updateOrInsert(
            ['loan_id' => $id],
            $data
        );


        $loan_applicant = DB::table('loan_application')
            ->where('id', $id)
            ->value('loan_applicant');

        if (!$loan_applicant) {
            return response()->json(['error' => 'Loan applicant not found'], 404);
        }

        $email = DB::table('users')
            ->where('id', $loan_applicant)
            ->where('status', 1)
            ->value('email');

        if (!$email) {
            return response()->json(['error' => 'Valid user email not found'], 404);
        }

        $htmlContent = view('components.emails.for_revision_email')->render();

        $config = Configuration::getDefaultConfiguration()
        ->setApiKey('api-key', config('services.brevo.key'));

        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
        $emailObj = new SendSmtpEmail([
            'subject'     => 'Loan Request Review – Additional Information Needed',
            'sender'      => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
            'to'          => [['email' => $email]],
            'htmlContent' => $htmlContent
        ]);

        try {
            $apiInstance->sendTransacEmail($emailObj);
            return response()->json(1);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }

        return response()->json([
            'message' => $email,
        ]);
    }

   public function updateLoanStatus(Request $request)
    {
        $request->validate([
            'loan_id'   => 'required|integer|exists:loan_application,id',
            'status_id' => 'required|integer|exists:loan_status,id',
        ]);

        try {
           
            DB::table('loan_application')
                ->where('id', $request->loan_id)
                ->update([
                    'loan_status' => $request->status_id,
                    'updated_at'  => now()
                ]);

            $updated_to = DB::table('loan_status')
                ->where('id', $request->status_id)
                ->value('loan_status'); 

            
            ActivityLogger::log(
                'Update Status',
                "Update Status to <b>{$updated_to}</b>",
                $request->loan_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Loan status updated successfully',
                'new_status' => $updated_to
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loan status',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getBankDetails(Request $request)
    {
        $loan_id = $request->loan_id;

        // Get bank details
        $bank_details = DB::table('loan_application')
            ->select(
                'bank_name',
                'account_number',
                DB::raw("CONCAT('" . asset('storage') . "/', upload_qr_code_img) as upload_qr_code_img")
            )
            ->where('id', $loan_id)
            ->where('status', 1)
            ->first();

        $user = auth()->user();

        // processed_by )
        if ($bank_details) {
            $bank_details->processed_by = $user->firstname . ' ' . $user->lastname;
        }

        return response()->json($bank_details);
    }

    public function transferMoeny(Request $request)
    {
        $path = $request->file('screenshot')->store('uploads/money_transfer', 'public');

        $userId = auth()->id();
        
        // Insert into DB
        DB::table('admin_money_transfer')->insert([
            'loan_id'          => $request->loan_id,
            'reference_number' => $request->ref_number,
            'proof_of_transfer'       => $path, // stored path
            'remarks'          => $request->remarks,
            'processed_by'     => $userId,
            'transfer_date'    => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        //PROCESS APPROVED LOAN 
         // Get loan application
        $loanApplications = DB::table('loan_application')
            ->where('id', $request->loan_id)
            ->first();

        if (!$loanApplications) {
            return response()->json([
                'success' => false,
                'message' => 'Loan application not found.',
            ], 404);
        }

        // Compute monthly principal + interest
        $monthly  = $loanApplications->loan_amount / $loanApplications->loan_tenure;
        $interest = $loanApplications->loan_amount * $loanApplications->interest_rate;

        // Insert schedule
        for ($i = 1; $i <= $loanApplications->loan_tenure; $i++) {

            // Due date calculation (based on monthly_due_date instead of static now)
            $dueDate = Carbon::parse($request->monthly_due_date)
                ->subDay()
                ->addMonths($i - 1) // start from given due date
                ->endOfDay();

            // Insert into loan_tenure
            $tenureId = DB::table('loan_tenure')->insertGetId([
                'loan_id'           => $request->loan_id,
                'date'              => $dueDate,
                'principal'         => $monthly,
                'count'             => $i,
                'payment_status_id' => 1,
                'payment_id'        => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Insert corresponding interest
            DB::table('loan_tenure_interest')->insert([
                'tenure_id'         => $tenureId,
                'interest'          => $interest,
                'payment_status_id' => 1,
                'payment_id'        => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

         DB::table('loan_application')
            ->where('id', $request->loan_id)
            ->update([
                'loan_status' => 5, 
                'updated_at'  => now(),
            ]);

         return response()->json([
            'success' => true,
            'message' => 'Transfer successful!',
        ]);
    }

}
