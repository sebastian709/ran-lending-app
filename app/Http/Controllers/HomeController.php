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
        $loanApplication = DB::selectOne("SELECT *,DATE_FORMAT(DATE_ADD(DATE(updated_at), INTERVAL 90 DAY),'%W, %M %d, %Y') penalty_day FROM loan_application 
                WHERE loan_applicant = ? 
                ORDER BY created_at DESC 
                LIMIT 1",[$userId]
            );

        // dd($loanApplication);
        $loanStatus = $loanApplication->loan_status ?? 999;

        $hasFeedback = DB::table('engagement_feedback')
            ->where('user_id', $userId)
            ->exists();

        if ($loanStatus < 4 || $loanStatus == 999 || $loanStatus == 7) {
            return view('borrower.pages.home',  compact('loanStatus', 'hasFeedback','loanApplication'));
        }

        $data = DB::table('loan_application as la')
            ->leftJoin('loan_tenure as lt', 'lt.loan_id', '=', 'la.id')
            ->leftJoin('loan_tenure_interest as lti', 'lti.tenure_id', '=', 'lt.id')
            ->leftJoin('loan_tenure_penalty as ltp', 'ltp.tenure_id', '=', 'lt.id')
            ->where('lt.loan_id', $loanApplication->id)
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

        $nextPayment = DB::selectOne("SELECT 
                            lt.loan_id,
                            lt.date,
                            lt.principal,
                            lti.interest,
                            (
                            IF(lt.payment_status_id = 1, lt.principal, 0) +
                            IF(lti.payment_status_id = 1, lti.interest, 0) +
                            IF(ltp.payment_status_id = 1, sum(ltp.penalty), 0)
                            ) AS total_all
                        FROM loan_tenure AS lt
                        LEFT JOIN loan_tenure_interest AS lti 
                            ON lti.tenure_id = lt.id
                        LEFT JOIN loan_tenure_penalty AS ltp 
                            ON ltp.tenure_id = lt.id
                        WHERE lt.loan_id = ?
                        AND lt.date <= ?
                        GROUP BY lt.loan_id, lt.date, lt.principal, lti.interest
                            having total_all > 0
                            " , [$loanApplication->id,Carbon::now()->endOfMonth()->endOfDay()]);

        $is_new = DB::Select("SELECT count(id) id from loan_payments where loan_application_id = ?",[$loanApplication->id]);
        $isnew = $is_new['0']->id;
        if ($data->months === 0) {
            // $loanStatus = 0;
            return view('borrower.layouts.payment-state', compact('loanStatus', 'hasFeedback','loanApplication'));
        }

        // dd($loanStatus,$data,$nextPayment,$is_new);

        return view('borrower.pages.home', compact('loanStatus', 'data', 'nextPayment', 'isnew', 'hasFeedback'));
    }

    public function repayment_schedule()
    {
        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', auth()->id())
            ->orderBy('created_at', 'desc')
            ->first();
        // dd($loanApplication->id);
        $loanStatus = $loanApplication->loan_status ?? 999;

        $userId = auth()->id();
        $hasFeedback = DB::table('engagement_feedback')
            ->where('user_id', $userId)
            ->exists();

        if ($loanStatus <= 4 || $loanStatus == 999 || $loanStatus == 7) {
            return view('borrower.pages.home', compact('loanStatus','loanApplication','hasFeedback'));
        }

        $results = DB::select("SELECT
                lt.date,
                MAX(
                    IF(
                        (IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) = 0,
                        (SELECT DATE_FORMAT(updated_at, '%M %e, %Y') 
                        FROM loan_payments 
                        WHERE id = IF(lt.payment_id > lti.payment_id, lt.payment_id, lti.payment_id)),
                        0
                    )
                ) AS paid_date,
                MAX(
                    IF(
                        (IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) = 0,1,
                        IF(lt.date >= DATE_ADD(CURDATE(), INTERVAL 1 DAY),2,0)
                    )
                ) AS payment_status,
                SUM(IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) AS total,
                SUM(IF(lt.payment_status_id != 1, 0, lt.principal)) AS principal,
                SUM(IF(lti.payment_status_id != 1, 0, lti.interest)) AS interest,
                IFNULL(SUM(ltp.penalty), 0) AS penalty,
                MAX(
                    IF(
                        (IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) = 0,
                        0,
                        IF((IF(lt.payment_status_id != 1, 0, lt.principal) + IF(lti.payment_status_id != 1, 0, lti.interest)) != (lt.principal + lti.interest),'p',0)
                    )
                ) AS partial,
                MAX(
                    IF(
                        ((SELECT payment_status_id FROM loan_payments WHERE id = lt.payment_id) = 1 OR
                        (SELECT payment_status_id FROM loan_payments WHERE id = lti.payment_id) = 1 OR
                        (SELECT payment_status_id FROM loan_payments WHERE id = ltp.payment_id) = 1),1,0
                    )
                ) AS verification
            FROM loan_application AS la
            LEFT JOIN loan_tenure AS lt ON lt.loan_id = la.id
            LEFT JOIN loan_tenure_interest AS lti ON lti.tenure_id = lt.id
            LEFT JOIN loan_tenure_penalty AS ltp ON ltp.tenure_id = lt.id
            WHERE la.id = ?
            GROUP BY lt.date",[$loanApplication->id]);

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
                    'user_incomes.specified_others',
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

            // last_closed_loan_data
            $last_closed = DB::table('loan_application')
                ->where('loan_applicant', $id)
                ->where('status', 1)
                ->where('loan_status', 7)
                ->orderBy('id', 'desc')
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
                'specified_others' => $income->specified_others ?? '',
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
                'loan_type' => $loan->loan_type ?? '',
                'scheduled_date' => $loan->scheduled_date ?? '',

                // Additional Fields
                'payslip_img' => $loan->payslip_img ?? '',
                'bank_name' => $last_closed->bank_name ?? '',
                'account_number' => $last_closed->account_number ?? '',
                'upload_qr_code_img' => $last_closed->upload_qr_code_img ?? '',
                'government_type_id' => $last_closed->government_type_id ?? '',
                'government_id_img' => $last_closed->government_id_img ?? '',
                'billing_statement_img' => $last_closed->billing_statement_img ?? '',
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

        // Clean numeric fields
        $request->merge([
            'income' => str_replace(',', '', $request->income),
        ]);

        $validated = $request->validate([
            'load_step' => 'required|integer',
            'purpose_of_loan' => 'nullable|string',
            'specify_others' => 'nullable|string',
            'referral' => 'nullable|string',
            'referral_code_id' => 'nullable|integer',
            'occupation' => 'required|string',
            'income' => 'required|numeric',
            'employmentStatus' => 'required|integer',
            'loan_type' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
        ]);

        $existing = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->where('loan_status', 0)
            ->first();
        // dd($existing);

        if ($existing) {
            // Update user income
            DB::table('user_incomes')
                ->where('user_id', $userId)
                ->update([
                    'occupation' => $validated['occupation'],
                    'income' => $validated['income'],
                    'employment_status' => $validated['employmentStatus'],
                    'specified_others' => $validated['specify_others'],
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
                    'loan_type' => $validated['loan_type'],
                    'scheduled_date' => $validated['scheduled_date'],
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
                        'specified_others' => $validated['specify_others'],
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
                'loan_type' => $validated['loan_type'],
                'scheduled_date' => $validated['scheduled_date'],
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
        try {
            $userId = auth()->id();

            // Check if user has previous CLOSED loan
            $loan_closed = DB::table('loan_application')
                ->where('loan_applicant', $userId)
                ->where('status', 1)
                ->where('loan_status', 7)
                ->orderBy('id', 'desc')
                ->first();

            // Prepare old files
            $old_govid = $loan_closed->government_id_img ?? null;
            $old_billing = $loan_closed->billing_statement_img ?? null;
            $old_qr = $loan_closed->upload_qr_code_img ?? null;

            // -----------------------------
            // ✔ Dynamic Validation Rules
            // -----------------------------
            $rules = [
                'loan_application_id' => 'required|integer',
                'load_step' => 'required|integer',
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'government_type_id' => 'required|integer',
                'payslip_img' => 'file|mimes:jpg,jpeg,png,pdf',
                'signature_img' => 'required|string',
            ];

            // If NO previous closed loan → require uploads
            if (!$loan_closed) {
                $rules['government_id_img'] = 'required|file|mimes:jpg,jpeg,png';
                $rules['billing_statement_img'] = 'required|file|mimes:jpg,jpeg,png,pdf';
            } else {
                // Not required for 2nd loan and up
                $rules['government_id_img'] = 'file|mimes:jpg,jpeg,png';
                $rules['billing_statement_img'] = 'file|mimes:jpg,jpeg,png,pdf';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Data to update
            $data = [
                'load_step' => $request->load_step,
                'loan_status' => 1,
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
                    // NEW UPLOAD
                    $file = $request->file($requestField);
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("storage/uploads/{$info['folder']}"), $filename);
                    $data[$info['db_field']] = "uploads/{$info['folder']}/{$filename}";
                } else {
                    // NO UPLOAD + MAY CLOSED LOAN → use OLD DATA
                    if ($loan_closed) {
                        if ($requestField === 'government_id_img') {
                            $data[$info['db_field']] = $old_govid;
                        }
                        if ($requestField === 'billing_statement_img') {
                            $data[$info['db_field']] = $old_billing;
                        }
                        if ($requestField === 'qr_code_img') {
                            $data[$info['db_field']] = $old_qr;
                        }
                    }
                }
            }

            // Signature base64 handling
            if ($request->filled('signature_img')) {
                $base64 = $request->input('signature_img');
                if (Str::startsWith($base64, 'data:image')) {
                    $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
                    $filename = uniqid() . '.png';

                    $directory = public_path("storage/uploads/signature");
                    if (!file_exists($directory))
                        mkdir($directory, 0775, true);

                    file_put_contents("{$directory}/{$filename}", $image);
                    $data['signature_img'] = "uploads/signature/{$filename}";
                }
            }

            // Update loan
            DB::table('loan_application')
                ->where('id', $request->loan_application_id)
                ->update($data);

            // Send Confirmation Email
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
            $apiInstance->sendTransacEmail($emailObj);

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
        $remarks = $rejected_fields->first()->amount_remarks;

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
                'remarks',
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
                'qr_code_img' => ['folder' => 'qr_codes', 'db_field' => 'upload_qr_code_img'],
                'government_id_img' => ['folder' => 'government_id', 'db_field' => 'government_id_img'],
                'billing_statement_img' => ['folder' => 'billing_statement', 'db_field' => 'billing_statement_img'],
            ];


            $updateData = [];

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

    public function checkLoanData()
    {
        $userId = auth()->id();

        $loanApplication = DB::table('loan_application')
            ->where('loan_applicant', $userId)
            ->where('loan_status', 5)
            ->where('make_appeal', 1)
            ->orderBy('created_at', 'desc')
            ->first();

        if (isset($loanApplication)) {
            return response()->json([
                'loan_id' => $loanApplication->id,
                'loan_status' => $loanApplication->loan_status,
                'make_appeal' => $loanApplication->make_appeal,
            ]);
        }else{
            return response()->json([
                'loan_id' => 0,
                'loan_status' => 0,
                'make_appeal' => 0,
            ]);
        }
    }


    public function updateAppealStatus(Request $request)
    {
        $loan_id = $request->input('loan_id');

        DB::table('loan_application')
            ->where('id', $loan_id)
            ->update(['make_appeal' => 0]);

        return response()->json(['status' => 'ok']);
    }

    public function saveAppeal(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|integer',
            'reason' => 'required|string',
            'uploaded_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $updateData = [
            'loan_id' => $request->loan_id,
            'reason' => $request->reason,
            'date_of_appeal' => now(),
            'uploaded_proof' => null, // default
        ];

        // File upload handling
        $folderMap = [
            'uploaded_proof' => ['folder' => 'loan_appeal', 'db_field' => 'uploaded_proof'],
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

        return response()->json([
            'status' => 'success',
            'message' => 'Appeal submitted successfully',
            'name' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
        ]);
    }
    
    public function rejectaccept(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $payment_id = DB::selectOne("SELECT * from loan_payment_approval_logs where id = ?",[$request->log_id]);
        
        DB::update('UPDATE loan_payments SET cancelled_approved_date = ? WHERE id = ?', [ Carbon::now() ,$payment_id->id]);
        DB::update('UPDATE loan_payment_approval_logs SET updated_at = ?,declined_accepted = 1 WHERE id = ?', [Carbon::now(),$request->log_id]);

        DB::update('UPDATE loan_tenure SET payment_status_id = 1,payment_id = 0 WHERE payment_id = ?', [$payment_id->id]);
        DB::update('UPDATE loan_tenure_interest SET payment_status_id = 1,payment_id = 0 WHERE payment_id = ?', [$payment_id->id]);
        DB::update('UPDATE loan_tenure_penalty SET payment_status_id = 1,payment_id = 0 WHERE payment_id = ?', [$payment_id->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Accepted successfully',
        ]);
    }

    public function EngagementSubmit(Request $request)
    {
           $request->validate([
                'user_id' => 'required|integer',
                'social_media' => 'required|string',
                'referral' => 'required|string',
            ]);

            $id = DB::table('engagement_feedback')->insertGetId([
                'user_id' => $request->user_id,
                'social_media' => $request->social_media,
                'referral' => $request->referral,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'feedback_id' => $id
            ]);
    }




}
