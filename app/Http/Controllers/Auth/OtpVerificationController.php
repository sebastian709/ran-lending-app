<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;

class OtpVerificationController extends Controller
{
    public function showForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        // OTP is valid — create reset token and redirect
        $token = Password::createToken(\App\Models\User::where('email', $request->email)->first());

        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email]);
    }
}
