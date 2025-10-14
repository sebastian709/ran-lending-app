<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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



    public function get_pending(Request $request)
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
            'lps.type',
            'lp.id'
        ])
        ->join('loan_payment_statuses as lps', 'lps.id', '=', 'lp.payment_status_id')
        ->join('loan_application as la', 'la.id', '=', 'lp.loan_application_id')
        ->join('users as u', 'u.id', '=', 'lp.loan_application_id'); // <-- check this join!
        // ->get();
        // if (!empty($search)) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('name', 'like', "%$search%")
        //           ->orWhere('email', 'like', "%$search%");
        //     });
        // }

        $recordsFiltered = $query->count();

        // Pagination
        $pendings = $query->skip($start)->take($length)->get();
            // dd($pending);
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
}
