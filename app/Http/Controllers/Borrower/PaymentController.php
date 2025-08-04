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

class PaymentController extends Controller
{
    public function isValidAmount($value) {
        return is_numeric($value) && $value != 0;
    }
    public function index()
    {
        $data = [];


        $data['loan_application'] = loan_application::
            where('loan_applicant', auth()->id())
            ->where('status', 1)
            ->first();

        if ($data['loan_application'] === null) {
            return redirect('/apply-loan');
        }


        //unpaid till today
        $data['loan_tenure_delayed_pay'] = loan_tenure::
        where('date', '<=', Carbon::today()->endOfDay())
        ->where('payment_status_id', 1)
        ->get();

        //Payable/Deadline wthinin 7 days
        // $data['loan_tenure_next_pay'] = loan_tenure::whereBetween('date', [
        //     now(),
        //     now()->addDays(7)
        // ])->first();


        $data['loan_tenure_next_pay'] = DB::table('loan_tenure as lt')
            ->select(
                'lt.id',
                'la.interest_rate',
                'lt.date',
                'lt.principal',
                'lti.interest',
                'lt.count',
                DB::raw('SUM(ltp.penalty) as penalty'),
                DB::raw('(lt.principal + lti.interest + IFNULL(SUM(ltp.penalty), 0)) as total'),
                DB::raw('(SELECT count(count) FROM loan_tenure WHERE loan_id = lt.id ) as months')

            )
            ->join('loan_application as la', function ($join) {
                $join->on('la.id', '=', 'lt.loan_id')
                    ->where('la.status', 1);
            })
            ->join('loan_tenure_interest as lti', function ($join) {
                $join->on('lti.tenure_id', '=', 'lt.id')
                    ->where('lti.payment_status_id', 1);
            })
            ->leftJoin('loan_tenure_penalty as ltp', function ($join) {
                $join->on('ltp.tenure_id', '=', 'lt.id')
                    ->where('ltp.payment_status_id', 1);
            })
            // ->whereBetween('lt.date', [now(), now()->addDays(7)])
            ->where('lt.payment_status_id','>',0 )
            ->where('la.loan_applicant',$data['loan_application']->id  )
            ->groupBy(  'la.id',
                                'la.interest_rate',
                                'lt.date', 
                                'lt.principal',                         
                                'lti.interest',
                                'lt.count',
                                'lt.id',
                                'lt.loan_id')
            ->first();


        // dd($data['loan_tenure_next_pay']);



            $data['records']['loan'] = loan_tenure::join('loan_tenure_interest as lti', function ($join) {
                $join->on('lti.tenure_id', '=', 'loan_tenure.id')
                     ->where('lti.payment_status_id', 1);
            })
            ->where('loan_tenure.payment_status_id', 1)
            ->where('loan_tenure.loan_id', $data['loan_application']->id)
            ->where('loan_tenure.id','!=', $data['loan_tenure_next_pay']->id)
            ->select(
                'loan_tenure.id as tenure_id',
                'lti.id as interest_id',
                'loan_tenure.date',
                'loan_tenure.principal',
                'lti.interest',
                'loan_tenure.count'
            )
            ->get();
        
            
                
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


        $Loan_payment = Loan_payment::insertGetId([
            'amount_sent'       => $request->total,
            'loan_application_id' => $request->id,
            'payment_status_id' => '1',
            'payment_type_id' => $request->type,
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
    
    

        if($request->type == 1){
            dd('1');
        }elseif ($request->type == 2) {
            $nextId = $request->next_id;
            $paymentId = $Loan_payment;

            if ($this->isValidAmount($request->partial_principal)) {
                loan_tenure::where('id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            if ($this->isValidAmount($request->partial_interest)) {
                loan_tenure_interest::where('tenure_id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            if ($this->isValidAmount($request->partial_penalty)) {
                loan_tenure_penalty::where('tenure_id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            dd('2');
        }elseif ($request->type == 3) {
            $nextId = $request->next_id;
            $paymentId = $Loan_payment;

            if ($this->isValidAmount($request->partial_principal)) {
                loan_tenure::where('id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            if ($this->isValidAmount($request->partial_interest)) {
                loan_tenure_interest::where('tenure_id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            if ($this->isValidAmount($request->partial_penalty)) {
                loan_tenure_penalty::where('tenure_id', $nextId)->update([
                    'payment_id' => $paymentId,
                    'payment_status_id' => 2,
                ]);
            }

            dd('3');
        }elseif ($request->type == 4) {

            foreach ($request->paymentData as $key => $record) {
                loan_tenure::where('id',  $record['id'])
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_interest::where('tenure_id',  $record['id'])
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
                loan_tenure_penalty::where('tenure_id',  $record['id'])
                ->update([
                    'payment_id' => $Loan_payment,
                    'payment_status_id' => 2,
                ]);
            }
            // dd('4');
            
            
        }else{
            dd('error');
        }
        
        return response()->json([
            'success' => true,
            'redirect' => url('/payment-success')
        ]);

     }
    
}
