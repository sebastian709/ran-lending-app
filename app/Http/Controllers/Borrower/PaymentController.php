<?php

namespace App\Http\Controllers\Borrower;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Models\loan\loan_application;
use App\Models\loan\loan_tenure;
use App\Models\loan\loan_tenure_interest;
use App\Models\loan\loan_tenure_penalty;
use App\Models\loan\loan_payment;
use App\Http\Controllers\HomeController; 

class PaymentController extends Controller
{
    public function isValidAmount($value) {
        return is_numeric($value) && $value != 0;
    }
    public function index()
    {
        $data = [];
        $homeController = new HomeController();
        $loanStatus = $homeController->getLoanStatus();

        $data['loan_application'] = loan_application::
            where('loan_applicant', auth()->id())
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        // dd($loanStatus)

        if ($loanStatus < 4 || $loanStatus == 999 || $loanStatus == 7) {
            return view('borrower.layouts.payment-state', compact('loanStatus'));
        }


        //unpaid till today
        $data['loan_tenure_delayed_pay'] = loan_tenure::
        where('date', '<=', Carbon::today()->endOfDay())
        ->where('payment_status_id', 1)
        ->get();

        // DB::enableQueryLog();
        $data['to_pay'] = DB::table('loan_tenure as lt')
        ->select(
            'lt.id',
            'la.interest_rate',
            'lt.date',
            'lti.id',
            DB::raw('if(lt.payment_status_id != 1,0,lt.principal) as principal'),
            DB::raw('if(lti.payment_status_id != 1,0,lti.interest) as interest'),
            'lt.count',
            DB::raw('SUM(ltp.penalty) as penalty'),
            DB::raw('(if(lt.payment_status_id != 1,0,lt.principal) + if(lti.payment_status_id != 1,0,lti.interest) + IFNULL(SUM(ltp.penalty), 0)) as total'),
            DB::raw('(SELECT count(ls.count) FROM loan_tenure ls WHERE ls.loan_id = la.id ) as months'),
            DB::raw('IF((lt.payment_status_id > 1 || lti.payment_status_id > 1),1,0) partial')

        )
        ->join('loan_application as la', function ($join) {
            $join->on('la.id', '=', 'lt.loan_id')
                ->where('la.status', 1);
        })
        ->join('loan_tenure_interest as lti', function ($join) {
            $join->on('lti.tenure_id', '=', 'lt.id');
        })
        ->leftJoin('loan_tenure_penalty as ltp', function ($join) {
            $join->on('ltp.tenure_id', '=', 'lt.id');
        })
        ->where('lt.payment_status_id','>',0 )
        ->where(function ($query) {$query->where('lt.payment_status_id', '<=', 1)->orWhere('lti.payment_status_id', '<=', 1);})
        ->where('la.loan_applicant',$data['loan_application']->loan_applicant  )
        ->where('date', '<', Carbon::now()->addMonth()->endOfMonth())
        ->groupBy(  'la.id',
                            'la.interest_rate',
                            'lt.date', 
                            'lt.principal',                         
                            'lti.interest',
                            'lt.count',
                            'lt.id',
                            'lti.id',
                            'lt.payment_status_id',
                            'lti.payment_status_id',
                            'lt.loan_id')
        ->get();


        // dd(DB::getQueryLog());
        // dd($data['loan_application']);
        
        if (empty($data['to_pay']) || $data['to_pay']->isEmpty()) {
        // dd("data['to_pay']");

            $data['to_pay'] = DB::table('loan_tenure as lt')
                ->select(
                    'lt.id',
                    'la.interest_rate',
                    'lt.date',
                    'lti.id',
                    DB::raw('if(lt.payment_status_id != 1,0,lt.principal) as principal'),
                    DB::raw('if(lti.payment_status_id != 1,0,lti.interest) as interest'),
                    'lt.count',
                    DB::raw('SUM(ltp.penalty) as penalty'),
                    DB::raw('(if(lt.payment_status_id != 1,0,lt.principal) + if(lti.payment_status_id != 1,0,lti.interest) + IFNULL(SUM(ltp.penalty), 0)) as total'),
                    DB::raw('(SELECT count(ls.count) FROM loan_tenure ls WHERE ls.loan_id = la.id ) as months'),
                    DB::raw('IF((lt.payment_status_id > 1 || lti.payment_status_id > 1),1,0) partial')
                )
                ->join('loan_application as la', function ($join) {
                    $join->on('la.id', '=', 'lt.loan_id')
                        ->where('la.status', 1);
                })
                ->join('loan_tenure_interest as lti', function ($join) {
                    $join->on('lti.tenure_id', '=', 'lt.id');
                })
                ->leftJoin('loan_tenure_penalty as ltp', function ($join) {
                    $join->on('ltp.tenure_id', '=', 'lt.id');
                })
                ->where('lt.payment_status_id','>',0 )
                ->where(function ($query) {$query->where('lt.payment_status_id', '<=', 1)->orWhere('lti.payment_status_id', '<=', 1);})
                ->where('la.loan_applicant',$data['loan_application']->loan_applicant )
                ->limit(1) 
                // ->where('date', '<', Carbon::now()->addMonth()->endOfMonth())



                // where payment_status_id = 1 and payment_id = 0
                ->groupBy(  'la.id',
                                    'la.interest_rate',
                                    'lt.date', 
                                    'lt.principal',                         
                                    'lti.interest',
                                    'lt.count',
                                    'lt.id',
                                    'lti.id',
                                    'lt.payment_status_id',
                                    'lti.payment_status_id',
                                    'lt.loan_id')
                ->get();

        }

        $ids = $data['to_pay']->pluck('id')->toArray();      
        // IF HAS ACTIVE LOAN BUT NOTHING TO PAY (FOR NOW)
    // dd($data['to_pay']);

        if ($data['to_pay']->isEmpty()) {
            $loanStatus = 3; 
            return view('borrower.layouts.payment-state', compact('loanStatus'));
        }

        $data['records']['loan'] = loan_tenure::join('loan_tenure_interest as lti', function ($join) {
            $join->on('lti.tenure_id', '=', 'loan_tenure.id')
                    ->where('lti.payment_status_id', 1);
        })
        ->where('loan_tenure.payment_status_id', 1)
        ->where('loan_tenure.loan_id', $data['loan_application']->id)
        ->whereNotIn('loan_tenure.id', $ids)
        ->select(
            'loan_tenure.id as tenure_id',
            'lti.id as interest_id',
            'loan_tenure.date',
            'loan_tenure.principal',
            'lti.interest',
            'loan_tenure.count'
        )
        ->get();
        // dd( $data['records']['loan'] , $ids , $data['loan_application']->id);
        foreach ($data['records']['loan'] as $key => $record) {
            $penalties = DB::table('loan_tenure_penalty')
                ->where('tenure_id', $record->tenure_id)
                ->where('payment_status_id', 1)
                ->get();
            
            $data['records']['loan'][$key]->penalties = $penalties;
        }

    // dd($data);
    return view('borrower.pages.payments.payment', compact('data'));
    }


     public function submit(Request $request){
         $data = $request->all();
        //  dd($data);
        // PENDING PAYMENT TYPE TO SAVE
        
        
        if (isset($request->paymentData)) {
            $type = 3;
        }
        if (isset($request->paymentPar)) {
            $type = 2;
        }
        if ($request->fullpayment == 1) {
            $type = 1;
        }
        if (!isset($request->paymentPar) && !isset($request->paymentData) && $request->fullpayment == 0) {
            $type = 4;
        }

        // dd($type);
        
        $Loan_payment = Loan_payment::insertGetId([
            'amount_sent'       => $request->total,
            'loan_application_id' => $request->id,
            'payment_status_id' => '1',
            'payment_type_id' => $type,
            'reference_code'    => $request->reference_code,
            'sent_to'           => '1',
            'remarks'           => $request->remarks,
            'attachment'        => '1',
            'added_by'          => auth()->id(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // categoryText = 'Full Payment';
        // categoryType = 1;
        // categoryText = 'Partial Payment';
        // categoryType = 2;
        // categoryText = 'Advanced Payment';
        // categoryType = 3;
        // categoryText = 'Normal Payment';
        // categoryType = 4;

        // if($request->type == 1){
        //     // dd('1');

        //     foreach ($request->paymentData as $key => $record) {
        //         $loan_tenure = loan_tenure::selectRaw("
        //             CASE 
        //                 WHEN date < DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 MONTH), '%Y-%m-01 00:00:00')
        //                 THEN '1' 
        //                 ELSE '0' 
        //             END AS dates
        //         ")
        //         ->where('id', $record['id'])
        //         ->first();

        //         loan_tenure::where('id',  $record['id'])
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //         loan_tenure_penalty::where('tenure_id',  $record['id'])
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
                
        //         //Current Month is not bound for Rebate
        //         if($loan_tenure['dates'] == '1'){

        //             loan_tenure_interest::where('tenure_id',  $record['id'])
        //             ->update([
        //                 'payment_id' => $Loan_payment,
        //                 'payment_status_id' => 2,
        //             ]);
        //         }else{
        //             //Month > current month is  bound for Rebate
        //             loan_tenure_interest::where('tenure_id',  $record['id'])
        //             ->update([
        //                 'payment_id' => $Loan_payment,
        //                 'payment_status_id' => 4,
        //             ]);
        //         }
        //     }
        // }elseif ($request->type == 2) {
        //     // dd('2');
        //     $nextId = $request->next_id;
        //     $paymentId = $Loan_payment;

        //     // foreach ($request->paymentDue as $key => $record) {
        //     //     loan_tenure::where('id',  $record['id'])
        //     //     ->where('payment_status_id', 1)
        //     //     ->update([
        //     //         'payment_id' => $record->id,
        //     //         'payment_status_id' => 2,
        //     //     ]);
        //     //     loan_tenure_penalty::where('tenure_id',  $record['id'])
        //     //     ->where('payment_status_id', 1)
        //     //     ->update([
        //     //         'payment_id' => $record->id,
        //     //         'payment_status_id' => 2,
        //     //     ]);
        //     //     loan_tenure_interest::where('tenure_id',  $record['id'])
        //     //     ->where('payment_status_id', 1)
        //     //     ->update([
        //     //         'payment_id' => $record->id,
        //     //         'payment_status_id' => 2,
        //     //     ]);
        //     // }



        //     if ($this->isValidAmount($request->partial_principal)) {
        //         loan_tenure::where('id', $nextId)
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $paymentId,
        //             'payment_status_id' => 2,
        //         ]);
        //     }

        //     if ($this->isValidAmount($request->partial_interest)) {
        //         loan_tenure_interest::where('tenure_id', $nextId)
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $paymentId,
        //             'payment_status_id' => 2,
        //         ]);
        //     }

        //     if ($this->isValidAmount($request->partial_penalty)) {
        //         loan_tenure_penalty::where('tenure_id', $nextId)
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $paymentId,
        //             'payment_status_id' => 2,
        //         ]);
        //     }

        // }elseif ($request->type == 3) {
        //     // dd('3');

        //     foreach ($request->paymentData as $key => $record) {
        //         loan_tenure::where('id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //         loan_tenure_penalty::where('tenure_id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //         loan_tenure_interest::where('tenure_id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //     }

        //     // dd('3');
        // }elseif ($request->type == 4) {
        //     // dd('4');

        //     foreach ($request->paymentDue as $key => $record) {
        //         loan_tenure::where('id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //         loan_tenure_interest::where('tenure_id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //         loan_tenure_penalty::where('tenure_id',  $record['id'])
        //         ->where('payment_status_id', 1)
        //         ->update([
        //             'payment_id' => $Loan_payment,
        //             'payment_status_id' => 2,
        //         ]);
        //     }
        //     // dd('4');
            
            
        // }else{
        //     dd('error');
        // }

        if ($request->fullpayment == 1) {
            // dd('if');
            if (isset($request->paymentDue)) {
                foreach ($request->paymentDue as $key => $record) {
                    loan_tenure::where('id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                    loan_tenure_penalty::where('tenure_id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                    loan_tenure_interest::where('tenure_id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                }
            }

            if (isset($request->paymentData)) {
                foreach ($request->paymentData as $key => $record) {
                    loan_tenure::where('id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                    loan_tenure_interest::where('tenure_id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 4,
                    ]);
                }
            }

            // dd('else');
            return response()->json([
                'success' => true,
                'redirect' => url('/payment-success')
            ]);
        }






        // PARTIAL
        if (isset($request->paymentPar)) {
            foreach ($request->paymentPar as $key => $record) {
                if (isset($record['principal'])) {
                    loan_tenure::where('id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                }
                if (isset($record['interest'])) {
                    loan_tenure_interest::where('tenure_id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                }
                if (isset($record['penalty'])) {
                    loan_tenure_penalty::where('tenure_id',  $record['id'])
                    ->where('payment_status_id', 1)
                    ->update([
                        'payment_id' => $Loan_payment,
                        'payment_status_id' => 2,
                    ]);
                }
                
            }
        }

        //NORMAL PAYMENT
        if (isset($request->paymentDue)) {
            foreach ($request->paymentDue as $key => $record) {
                loan_tenure::where('id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_penalty::where('tenure_id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_interest::where('tenure_id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
            }
        }
        

        //ADVANCED PAYMENT
        if (isset($request->paymentData)) {
            foreach ($request->paymentData as $key => $record) {
                loan_tenure::where('id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_penalty::where('tenure_id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_interest::where('tenure_id',  $record['id'])
                ->where('payment_status_id', 1)
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'redirect' => url('/payment-success')
        ]);

     }
    
}
