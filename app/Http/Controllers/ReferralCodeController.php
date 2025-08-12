<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.pages.referral.index');
    }

    public function store(Request $request)
    {
        // Check for duplicate referral_code
        $exists = DB::table('referral_code')
            ->where('referral_code', $request->referral_code)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'field' => 'referral_code',
                'message' => 'This referral code already exists.'
            ], 422);
        }

        // Insert new record
        DB::table('referral_code')->insert([
            'referral_code' => $request->referral_code,
            'description' => $request->description,
            'availability' => date('Y-m-d H:i:s', strtotime($request->availability)),
            'created_by' => auth()->id() ?? null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
    public function list(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10
        $page = $request->get('page', 1);

        $query = DB::table('referral_code')
            ->where('referral_code.status', 1);

        if ($request->search) {
            $query->where('referral_code.referral_code', 'like', "%{$request->search}%");
        }

        if ($request->filter !== null && $request->filter !== '' && $request->filter !== 'all') {
            $query->where('referral_code.is_active', $request->filter);
        }

        $query->leftJoin('users', 'users.id', '=', 'referral_code.created_by')
            ->select(
                'referral_code.id',
                'referral_code.referral_code',
                'referral_code.description',
                'referral_code.availability',
                'referral_code.created_at',
                'users.firstname',
                'users.lastname',
                'referral_code.is_active'
            );

        $total = $query->count(); // total records bago mag limit

        $rows = $query
            ->orderBy('referral_code.created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'referral_code' => $item->referral_code,
                    'description' => $item->description,
                    'availability' => $item->availability
                        ? date('M d, Y h:i A', strtotime($item->availability))
                        : null,
                    'created_at' => date('M d, Y h:i A', strtotime($item->created_at)),
                    'firstname' => $item->firstname,
                    'lastname' => $item->lastname,
                    'is_active' => $item->is_active
                ];
            });

        return response()->json([
            'data' => $rows,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page
        ]);
    }


    public function updateStatus(Request $request)
    {
        DB::table('referral_code')
            ->where('id', $request->id)
            ->update(['is_active' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        DB::table('referral_code')
            ->where('id', $request->id)
            ->update(['status' => 0]);

        return response()->json(['success' => true]);
    }

    public function checkReferralCode(Request $request)
    {
        $query = DB::table('referral_code')
            ->where('referral_code.status', 1)
            ->where('referral_code', $request->referral_code)
            ->where('is_active', 1)
            ->first();

        $is_found_code = 0;
        $referral_code_id = null; // default kapag walang nahanap

        if (!empty($query)) {
            $is_found_code = 1;
            $referral_code_id = $query->id;
        }

        return response()->json([
            "referral_code_id" => $referral_code_id,
            "is_found_code" => $is_found_code
        ]);
    }

}
