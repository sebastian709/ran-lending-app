<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private const REG_OTP_VERIFIED_EMAIL_KEY = 'reg_otp_verified_email';
    private const REG_OTP_VERIFIED_AT_KEY = 'reg_otp_verified_at';
    private const REG_OTP_WINDOW_MINUTES = 10;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'income' => str_replace(',', '', (string) $request->input('income')),
            'referral_names' => (string) $request->input('referral_source') === '2'
                ? $request->input('referral_names')
                : null,
            'specify_others' => (string) $request->input('employment_status') === '4'
                ? $request->input('specify_others')
                : null,
        ]);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'contactnumber' => ['required', 'regex:/^09\d{9}$/'],
            'referral_source' => 'required|in:1,2',
            'referral_names' => 'nullable|integer|min:1|required_if:referral_source,2',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'house_no' => 'nullable|string|max:255',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'income' => 'required|numeric|min:0|max:99999999.99',
            'employment_status' => 'required|in:1,2,3,4',
            'specify_others' => 'nullable|string|max:255|required_if:employment_status,4',
        ]);

        $email = $validated['email'];
        $verifiedEmail = strtolower((string) $request->session()->get(self::REG_OTP_VERIFIED_EMAIL_KEY, ''));
        $verifiedAtRaw = $request->session()->get(self::REG_OTP_VERIFIED_AT_KEY);
        $verifiedAt = $verifiedAtRaw ? Carbon::parse($verifiedAtRaw) : null;

        if (
            $verifiedEmail !== $email ||
            !$verifiedAt ||
            $verifiedAt->lt(now()->subMinutes(self::REG_OTP_WINDOW_MINUTES))
        ) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'Please verify your OTP before creating an account.',
                ]);
        }

        DB::transaction(function () use ($validated, $email) {
            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'middlename' => $validated['middlename'],
                'contactno' => $validated['contactnumber'],
                'is_referral' => (int) $validated['referral_source'],
                'referral_source_id' => (int) ($validated['referral_names'] ?? 0),
                'email' => $email,
                'password' => Hash::make($validated['password']),
            ]);

            UserDetails::create([
                'user_id' => $user->id,
                'house_no' => $validated['house_no'] ?? null,
                'street' => $validated['street'],
                'barangay' => $validated['barangay'],
                'city' => $validated['city'],
                'province' => $validated['province'],
            ]);

            UserIncome::create([
                'user_id' => $user->id,
                'occupation' => $validated['occupation'],
                'income' => $validated['income'],
                'employment_status' => (int) $validated['employment_status'],
                'specified_others' => $validated['specify_others'] ?? null,
            ]);
        });

        $request->session()->forget([
            self::REG_OTP_VERIFIED_EMAIL_KEY,
            self::REG_OTP_VERIFIED_AT_KEY,
        ]);

        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    }
}
