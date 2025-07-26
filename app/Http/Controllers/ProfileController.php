<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $userId = auth()->id();
        $usersInformation = DB::table('users')
            ->join('user_incomes', 'users.id', '=', 'user_incomes.user_id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'users.firstname',
                'users.lastname',
                'users.middlename',
                'users.username',
                'users.contactno',
                'users.email',
                'user_incomes.occupation',
                'user_incomes.income',
                'user_incomes.employment_status',
                'users.referral_source_id',
                // DB::raw('CONCAT(user_details.house_no, ", ", 
                //                        user_details.street, ", ", 
                //                        user_details.barangay, ", ",
                //                        user_details.city, ", ",
                //                        user_details.province) as complete_address')
                'user_details.house_no',
                'user_details.street',
                'user_details.barangay',
                'user_details.city',
                'user_details.province',
                'users.profile_src',
            )
            ->where('users.status', 1)
            ->where('users.id', $userId)
            ->first();


        return view('borrower.pages.profile', compact('usersInformation'));
    }

    public function update(Request $request)
    {
        $userId = auth()->id();

        try {
            // Start transaction
            DB::beginTransaction();

            // Update users table
            DB::table('users')->where('id', $userId)->update([
                'username' => $request->username,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'lastname' => $request->last_name,
                'referral_source_id' => $request->referral_source_id,
                'updated_at' => now()
            ]);

            // Update user_details table
            DB::table('user_details')->where('user_id', $userId)->update([
                'house_no' => $request->house_no,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'updated_at' => now()
            ]);
            $income = preg_replace('/[^\d.]/', '', $request->input('income'));

            // Update user_incomes table
            DB::table('user_incomes')->where('user_id', $userId)->update([
                'occupation' => $request->occupation,
                'income' => $income,
                'employment_status' => $request->employment_status,
                'updated_at' => now()
            ]);

            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Create folder if not exists
                if (!Storage::disk('public')->exists('upload/profile_picture')) {
                    Storage::disk('public')->makeDirectory('upload/profile_picture');
                }

                // Save the file
                $path = $file->storeAs('upload/profile_picture', $filename, 'public');

                // Save path in DB
                DB::table('users')->where('id', $userId)->update([
                    'profile_src' => $path,
                    'updated_at' => now()
                ]);
            }



            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword()
    {

        return view('borrower.pages.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.'
            ], 422); // HTTP 422: Unprocessable Entity
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Your password has been successfully changed.',
            'logout' => true
        ]);
    }

}
