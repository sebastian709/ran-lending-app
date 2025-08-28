<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ActivityLogger;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $userId = auth()->id();
        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        // dd($loanApplication);
        $loanStatus = $loanApplication->loan_status ?? 999;

        if ($loanStatus < 4 || $loanStatus == 999) {
            return view('borrower.pages.home', compact('loanStatus'));
        }

        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
        $data = DB::table('loan_application as la')
            ->leftJoin('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->leftJoin('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
            ->where('loan_id', $loanApplication->id )

            // ->where(function ($query) {
            //     $query->where('lt.payment_status_id', '<=', 1)
            //         ->orWhere('lti.payment_status_id', '<=', 1);
            // })
            ->select([
                DB::raw('(IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) AS current_payment'),
                'lt.date',
                DB::raw('IF(lt.payment_status_id != 1, 0, lt.principal) AS principal'),
                DB::raw('IF(lti.payment_status_id != 1, 0, lti.interest) AS interest'),

                DB::raw('lt.principal AS raw_principal'),
                DB::raw('lti.interest AS raw_interest'),
                DB::raw('(lti.interest + lt.principal) AS raw_total'),
        
                DB::raw('SUM(IF(lt.payment_status_id = 1, lt.principal, 0)) AS total_principal'),
                DB::raw('SUM(IF(lti.payment_status_id = 1, lti.interest, 0)) AS total_interest'),
                DB::raw('SUM(IF(ltp.payment_status_id = 1, ltp.penalty, 0)) AS total_penalty'),
        
                DB::raw('
                    (SUM(IF(lt.payment_status_id = 1, lt.principal, 0)) + 
                    SUM(IF(lti.payment_status_id = 1, lti.interest, 0)) + 
                    SUM(IF(ltp.payment_status_id = 1, ltp.penalty, 0))
                    ) AS total_all
                '),
                DB::raw('(SELECT count(ls.count) FROM loan_tenure ls WHERE ls.loan_id = la.id ) as months'),
                DB::raw('(SUM(lt.principal) + SUM(lti.interest) + IFNULL(SUM(ltp.penalty), 0)) AS total_all_raw'),
                DB::raw('SUM(lt.principal) AS total_principal_raw'),
                DB::raw('IFNULL(SUM(lti.interest), 0) AS total_interest_raw'),
                DB::raw('IFNULL(SUM(ltp.penalty), 0) AS total_penalty_raw'),
            ])
            ->first();
            
            
            $nextPayment = DB::table('loan_tenure as lt')
            ->select(
                'lt.loan_id',
                'lt.date',
                'lt.principal',
                'lti.interest',
                DB::raw('
                    (
                        SUM(IF(lt.payment_status_id = 1, lt.principal, 0)) +
                        SUM(IF(lti.payment_status_id = 1, lti.interest, 0)) +
                        SUM(IF(ltp.payment_status_id = 1, ltp.penalty, 0))
                    ) AS total_all
                ')
            )
            ->leftJoin('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
            ->where('lt.loan_id', $loanApplication->id)
            ->where('lt.date', '<=', Carbon::now()->endOfMonth()->endOfDay())
            ->groupBy('lt.loan_id', 'lt.date', 'lt.principal', 'lti.interest')
            ->first();



            if ($data->months === 0) {
                $loanStatus = 0;
                return view('borrower.layouts.payment-state', compact('loanStatus'));
            }
            
            
            // dd($loanStatus,$data,$datas);
            
        return view('borrower.pages.home', compact('loanStatus','data','nextPayment'));
    }

    public function repayment_schedule()
    {
        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', auth()->id())
            ->orderBy('created_at', 'desc')
            ->first();
            
        $results = DB::table('loan_application as la')
            ->leftJoin('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->leftJoin('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
            ->where('la.id',$loanApplication->id)
            ->select([
                'lt.date',
                DB::raw("
                    IF(
                        (IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) = 0,
                        (select DATE_FORMAT(updated_at, '%M %e, %Y') from loan_payments where id = if(lt.payment_id > lti.payment_id,lt.payment_id,lti.payment_id)),0)
                         AS paid_date
                "),
                DB::raw("
                    IF(
                        (IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) = 0,
                        1,
                        IF(
                            lt.date >= DATE_ADD(CURDATE(), INTERVAL 1 DAY),
                            2,
                            0
                        )
                    ) AS payment_status
                "),
                DB::raw("(IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) AS total"),
                DB::raw("IF(lt.payment_status_id != 1, 0, lt.principal) AS principal"),
                DB::raw("IF(lti.payment_status_id != 1, 0, lti.interest) AS interest"),
                DB::raw("IFNULL(IF(ltp.payment_status_id != 1, 0, ltp.penalty), 0) AS penalty"),
            ])
            ->groupBy(
                'la.id',
                'lt.date',
                'lt.payment_status_id',
                'lt.principal',
                'lt.payment_id',
                'lti.payment_status_id',
                'lti.interest',
                'lti.payment_id',
                'ltp.payment_status_id',
                'ltp.penalty'
            )
            ->get();

    //    dd($results);

        return view('borrower.pages.repayment-schedule', compact('results'));

    }

    public function loanApply()
    {
        $government_type = DB::table('government_type')
            ->where('status', 1)
            ->get();

        return view('borrower.pages.loan-apply', compact('government_type'));

    }

    public function fetchIncome($id)
    {
        try {
            // Fetch income info
            $income = DB::table('user_incomes')
                ->join('users', 'user_incomes.user_id', '=', 'users.id')
                ->select(
                    'user_incomes.occupation',
                    'user_incomes.income',
                    'user_incomes.employment_status',
                    DB::raw('CONCAT(users.firstname, " ", users.lastname) as fullname')
                )
                ->where('user_incomes.user_id', $id)
                ->where('user_incomes.status', 1)
                ->first();

            if (!$income) {
                return response()->json([
                    'message' => 'No income record found.'
                ], 404);
            }

            // Fetch loan application if exists
            $loan = DB::table('loan_application')
                ->where('loan_applicant', $id)
                ->where('status', 1)
                ->where('loan_status', 0)
                ->first();

            $ref_code = null;
            if ($loan && $loan->referral_code_id) {
                $ref_code = DB::table('referral_code')
                    ->where('id', $loan->referral_code_id) // <-- baka dapat referral_code_id instead of loan->id
                    ->first();
            }

            return response()->json([
                'loan_application_id' => $loan->id ?? '',
                'fullname' => $income->fullname ?? '',
                'occupation' => $income->occupation ?? '',
                'income' => $income->income ?? '',
                'employment_status' => $income->employment_status ?? '',
                'purpose_of_loan' => $loan->purpose_of_loan ?? '',
                'referral' => $loan->referral ?? '',
                'referral_code_id' => $loan->referral_code_id ?? '',
                'referral_code' => $ref_code->referral_code ?? '',
                'loan_amount' => $loan->loan_amount ?? '',
                'loan_tenure' => $loan->loan_tenure ?? '',
                'interest_rate' => $loan->interest_rate ?? '',
                'total_amount' => $loan->total_amount ?? '',

                // Additional Fields
                'payslip_img' => $loan->payslip_img ?? '',
                'bank_name' => $loan->bank_name ?? '',
                'account_number' => $loan->account_number ?? '',
                'upload_qr_code_img' => $loan->upload_qr_code_img ?? '',
                'government_type_id' => $loan->government_type_id ?? '',
                'government_id_img' => $loan->government_id_img ?? '',
                'billing_statement_img' => $loan->billing_statement_img ?? '',
                'signature_img' => $loan->signature_img ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function savePrecheck(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'load_step' => 'required|integer',
            'purpose_of_loan' => 'nullable|string',
            'referral' => 'nullable|string',
            'referral_code_id' => 'nullable|integer',
            'occupation' => 'required|string',
            'income' => 'required|numeric',
            'employmentStatus' => 'required|integer',
        ]);

        $existing = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->where('loan_application_id', 0)
            ->first();
        dd($existing);

        if ($existing) {
            // Update user income
            DB::table('user_incomes')
                ->where('user_id', $userId)
                ->update([
                    'occupation' => $validated['occupation'],
                    'income' => $validated['income'],
                    'employment_status' => $validated['employmentStatus'],
                    'updated_at' => now()
                ]);

            // Update existing loan
            DB::table('loan_application')
                ->where('id', $existing->id)
                ->update([
                    'purpose_of_loan' => $validated['purpose_of_loan'],
                    'referral' => $validated['referral'],
                    'referral_code_id' => $validated['referral_code_id'],
                    'load_step' => $validated['load_step'],
                    'updated_at' => now()
                ]);



            $loanId = $existing->id;
        } else {
            // Update or insert income record
            DB::table('user_incomes')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'occupation' => $validated['occupation'],
                        'income' => $validated['income'],
                        'employment_status' => $validated['employmentStatus'],
                        'updated_at' => now()
                    ]
                );
            // Insert new loan
            $loanId = DB::table('loan_application')->insertGetId([
                'load_step' => $validated['load_step'],
                'loan_applicant' => $userId,
                'purpose_of_loan' => $validated['purpose_of_loan'],
                'referral' => $validated['referral'],
                'referral_code_id' => $validated['referral_code_id'],
                'loan_status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);


        }

        if ($validated['referral_code_id']) {
            DB::table('referral_code')
                ->where('id', $validated['referral_code_id'])
                ->update([
                    'loan_id_claimant' => $loanId,
                    'is_active' => 0,
                    'updated_at' => now()
                ]);
        }

        return response()->json([
            'success' => true,
            'loan_application_id' => $loanId,
            'referral_type' => $validated['referral']
        ]);
    }


    public function updateLoanDetails(Request $request)
    {
        $userId = auth()->id();
        $validated = $request->validate([
            'load_step' => 'required|integer',
            'loan_amount' => 'required|numeric',
            'loan_tenure' => 'required|integer',
            'interest_rate' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        $loanApplicationId = $request->input('loan_application_id');

        if ($loanApplicationId) {
            // UPDATE flow
            $updated = DB::table('loan_application')
                ->where('id', $loanApplicationId)
                ->update([
                    'load_step' => $validated['load_step'],
                    'loan_amount' => $validated['loan_amount'],
                    'loan_tenure' => $validated['loan_tenure'],
                    'interest_rate' => $validated['interest_rate'],
                    'total_amount' => $validated['total_amount'],
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => $updated > 0,
                'message' => $updated ? 'Loan details updated successfully.' : 'No changes made.',
            ]);
        } else {
            // CREATE flow
            $newId = DB::table('loan_application')->insertGetId([
                'load_step' => $validated['load_step'],
                'loan_applicant' => $userId,
                'loan_status' => 0,
                'loan_amount' => $validated['loan_amount'],
                'loan_tenure' => $validated['loan_tenure'],
                'interest_rate' => $validated['interest_rate'],
                'total_amount' => $validated['total_amount'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loan application created successfully.',
                'loan_application_id' => $newId, // ibalik sa frontend
            ]);
        }
    }

    public function finalSubmit(Request $request)
    {
        // $this->loan_approved_process($request->loan_application_id);
        // dd('tests');
        try {
            $validator = Validator::make($request->all(), [
                'loan_application_id' => 'required|integer',
                'load_step' => 'required|integer',
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'government_type_id' => 'required|integer',
                'payslip_img' => 'required|file|mimes:jpg,jpeg,png,pdf',
                // 'qr_code_img' => 'file|mimes:jpg,jpeg,png',
                'government_id_img' => 'required|file|mimes:jpg,jpeg,png',
                'billing_statement_img' => 'required|file|mimes:jpg,jpeg,png,pdf',
                'signature_img' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'load_step' => $request->load_step,
                'loan_status' => 1, // Processing
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'government_type_id' => $request->government_type_id,
                'updated_at' => now(),
            ];

            // File handling
            $folderMap = [
                'payslip_img' => ['folder' => 'payslip', 'db_field' => 'payslip_img'],
                'qr_code_img' => ['folder' => 'qr_code', 'db_field' => 'upload_qr_code_img'],
                'government_id_img' => ['folder' => 'government_id', 'db_field' => 'government_id_img'],
                'billing_statement_img' => ['folder' => 'billing_statement', 'db_field' => 'billing_statement_img'],
            ];

            foreach ($folderMap as $requestField => $info) {
                if ($request->hasFile($requestField)) {
                    $file = $request->file($requestField);
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("storage/uploads/{$info['folder']}"), $filename);
                    $data[$info['db_field']] = "uploads/{$info['folder']}/{$filename}";
                }
            }

            // Signature base64 handling
            if ($request->filled('signature_img')) {
                $base64 = $request->input('signature_img');
                if (Str::startsWith($base64, 'data:image')) {
                    $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
                    $ext = 'png';
                    $filename = uniqid() . '.' . $ext;

                    $directory = public_path("storage/uploads/signature");
                    if (!file_exists($directory)) {
                        mkdir($directory, 0775, true);
                    }

                    $path = "{$directory}/{$filename}";
                    file_put_contents($path, $image);
                    $data['signature_img'] = "uploads/signature/{$filename}";
                }
            }

            DB::table('loan_application')
                ->where('id', $request->loan_application_id)
                ->update(values: $data);

            // Send confirmation email
            $email = auth()->user()->email;
            $htmlContent = view('components.emails.state_email')->render();
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.key'));
            $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
            $emailObj = new SendSmtpEmail([
                'subject' => '✅ Your Loan Application is Now Being Processed',
                'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
                'to' => [['email' => $email]],
                'htmlContent' => $htmlContent
            ]);
            $apiInstance->sendTransacEmail(sendSmtpEmail: $emailObj);
            //==============================================================

            $this->loan_approved_process($request->loan_application_id);
            return response()->json([
                'success' => true,
                'message' => 'Final application submitted successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Final Submit Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLoanStatus()
    {
        $userId = auth()->id();

        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        return $loanApplication->loan_status ?? 999;
    }

    public function loan_approved_process($data)
    {
        // dd($data);

        //PROCESS APPROVED LOAN 
        $loanApplications = DB::table('loan_application')
            ->where('id', $data)
            ->first();
        // dd($loanApplications);

        $monthly = $loanApplications->loan_amount / $loanApplications->loan_tenure;
        $interest = $loanApplications->loan_amount * $loanApplications->interest_rate;

        // dd($monthly);
        for ($i = 1; $i <= $loanApplications->loan_tenure; $i++) {

            //TENURE
            $date = Carbon::now('Asia/Manila') // current PH time
                ->subDay()
                ->addMonths($i)
                ->endOfDay();

            $ids = DB::table('loan_tenure')
                ->insertGetId(
                    [
                        'loan_id' => $data,
                        'date' => $date,
                        'principal' => $monthly,
                        'count' => $i,
                        'payment_status_id' => 1,
                        'payment_id' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

            //INTEREST
            $idss = DB::table('loan_tenure_interest')
                ->insertGetId(
                    [
                        'tenure_id' => $ids,
                        'interest' => $interest,
                        'payment_status_id' => 1,
                        'payment_id' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

        }

        return 1;
    }

    public function updateInformation()
    {
        $userId = auth()->id();

        $loan_id = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->where('loan_status', '<>', 7)
            ->value('id');

        $rejected_fields = DB::table('loan_rejected_fields')
            ->where('loan_id', $loan_id)
            ->get();

        $first_amount = intval($rejected_fields->first()->amount_suggested ?? 100);
        $max_amount = $first_amount;
        $summary_total = $max_amount + ($max_amount * 0.05);
        $loan_amount_rejected = $rejected_fields->first()->loan_amount;

        $payslip_img = $rejected_fields->first()->payslip_img;
        $upload_qr_code_img = $rejected_fields->first()->upload_qr_code_img;
        $government_id_img = $rejected_fields->first()->government_id_img;
        $billing_statement_img = $rejected_fields->first()->billing_statement_img;

        $loan_images = DB::table('loan_application')
            ->where('id', $loan_id)
            ->first();

        $loan_payslip_image = $loan_images->payslip_img;
        $loan_qr_image = $loan_images->upload_qr_code_img;
        $loan_id_image = $loan_images->government_id_img;
        $loan_billing_image = $loan_images->billing_statement_img;


        if (
            $loan_amount_rejected == 1 &&
            (!$payslip_img && !$upload_qr_code_img && !$government_id_img && !$billing_statement_img)
        ) {
            $step = 1; // only loan amount rejected
        } elseif (
            $loan_amount_rejected == 0 &&
            ($payslip_img || $upload_qr_code_img || $government_id_img || $billing_statement_img)
        ) {
            $step = 2; // only documents rejected
        } elseif (
            $loan_amount_rejected == 1 &&
            ($payslip_img || $upload_qr_code_img || $government_id_img || $billing_statement_img)
        ) {
            $step = 3; // both loan amount and some documents rejected
        } else {
            $step = 0; // none rejected
        }



        return view(
            'borrower.pages.update-information',
            compact(
                'rejected_fields',
                'first_amount',
                'max_amount',
                'summary_total',
                'loan_amount_rejected',
                'loan_id',
                'step',
                'loan_payslip_image',
                'loan_qr_image',
                'loan_id_image',
                'loan_billing_image',
                'payslip_img',
                'upload_qr_code_img',
                'government_id_img',
                'billing_statement_img'
            )
        );
    }

    public function resubmitLoanInfo(Request $request)
    {

        // dd(1);
        $userId = auth()->id();
        $validated = $request->validate([
            'loan_amount' => 'required|numeric',
            'loan_tenure' => 'required|integer',
            'interest_rate' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        $loanApplicationId = $request->input('loan_application_id');

        if ($loanApplicationId) {
            // UPDATE flow
            $updated = DB::table('loan_application')
                ->where('id', $loanApplicationId)
                ->update([
                    'loan_amount' => $validated['loan_amount'],
                    'loan_tenure' => $validated['loan_tenure'],
                    'interest_rate' => $validated['interest_rate'],
                    'total_amount' => $validated['total_amount'],
                    'updated_at' => now(),
                ]);

            $update_reject_field = DB::table('loan_rejected_fields')
                ->where('loan_id', $loanApplicationId)
                ->update([
                    'loan_amount' => 0,
                    // 'updated_at '=> now(),
                ]);

            $check_fields_updated = DB::table('loan_rejected_fields')
                ->where('loan_id', $loanApplicationId)
                ->first();

            if ($check_fields_updated) {
                // Check if all the specific fields are 0
                if (
                    $check_fields_updated->loan_amount == 0 &&
                    $check_fields_updated->payslip_img == 0 &&
                    $check_fields_updated->upload_qr_code_img == 0 &&
                    $check_fields_updated->government_id_img == 0 &&
                    $check_fields_updated->billing_statement_img == 0
                ) {
                    // Update loan_application status to 1 (proccess)
                    DB::table('loan_application')
                        ->where('id', $loanApplicationId)
                        ->update(['loan_status' => 1]);
                }
            }

            ActivityLogger::log('Accept Loan', 'Updated loan amount to ' . $validated['loan_amount'], $userId);

            return response()->json([
                'success' => $updated > 0,
                'message' => $updated ? 'Loan details updated successfully.' : 'No changes made.',
            ]);
        }
    }

    public function resubmitLoanDocuments(Request $request)
    {
        try {
            $loanId = $request->loan_id;

            if (!$loanId) {
                return response()->json(['status' => 'error', 'message' => 'Loan ID is missing'], 400);
            }

            // Map inputs to folders and DB columns
            $folderMap = [
                'payslip_img' => ['folder' => 'payslip', 'db_field' => 'payslip_img'],
                'qr_code_img' => ['folder' => 'qr_code', 'db_field' => 'upload_qr_code_img'],
                'government_id_img' => ['folder' => 'government_id', 'db_field' => 'government_id_img'],
                'billing_statement_img' => ['folder' => 'billing_statement', 'db_field' => 'billing_statement_img'],
            ];

            $updateData = [];

            foreach ($folderMap as $requestField => $info) {
                if ($request->hasFile($requestField)) {
                    $file = $request->file($requestField);

                    // Generate unique filename
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                    // Ensure folder exists
                    $directory = public_path("storage/uploads/{$info['folder']}");
                    if (!file_exists($directory)) {
                        mkdir($directory, 0775, true);
                    }

                    // Move file to storage
                    $file->move($directory, $filename);

                    // Save relative path to DB
                    $updateData[$info['db_field']] = "uploads/{$info['folder']}/{$filename}";
                }
            }

            $update_reject_field = DB::table('loan_rejected_fields')
                ->where('loan_id', $loanId)
                ->update([
                    'payslip_img' => 0,
                    'upload_qr_code_img' => 0,
                    'government_id_img' => 0,
                    'billing_statement_img' => 0
                ]);

            $check_fields_updated = DB::table('loan_rejected_fields')
                ->where('loan_id', $loanId)
                ->first();

            if ($check_fields_updated) {
                // Check if all the specific fields are 0
                if (
                    $check_fields_updated->loan_amount == 0 &&
                    $check_fields_updated->payslip_img == 0 &&
                    $check_fields_updated->upload_qr_code_img == 0 &&
                    $check_fields_updated->government_id_img == 0 &&
                    $check_fields_updated->billing_statement_img == 0
                ) {
                    // Update loan_application status to 1 (proccess)
                    DB::table('loan_application')
                        ->where('id', $loanId)
                        ->update(['loan_status' => 1]);
                }
            }

            $userId = auth()->id();
            ActivityLogger::log('Resubmit', 'Resubmit documents', $userId);

            if (!empty($updateData)) {
                $updated = DB::table('loan_application')
                    ->where('id', $loanId)
                    ->update($updateData);

                if ($updated) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Documents updated successfully!',
                        'data' => $updateData
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update loan application.'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No files uploaded.'
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Update Documents Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}

