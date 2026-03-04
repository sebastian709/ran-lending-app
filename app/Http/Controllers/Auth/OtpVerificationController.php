<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use GuzzleHttp\Client as GuzzleClient;

class OtpVerificationController extends Controller
{
    private const REG_OTP_VERIFIED_EMAIL_KEY = 'reg_otp_verified_email';
    private const REG_OTP_VERIFIED_AT_KEY = 'reg_otp_verified_at';

    public function showForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function regauthsend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim((string) $request->email));

        if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return response()->json(2);
        }

        $otp = random_int(100000, 999999);
        $htmlContent = view('components.emails.registration_otp', ['otp' => $otp])->render();

        DB::table('password_otps')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(3),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('services.brevo.key'));

        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

        $emailObj = new SendSmtpEmail([
            'subject' => 'RAN Lending Registration OTP',
            'sender' => ['name' => 'RAN Lending', 'email' => 'lordanniel@gmail.com'],
            'to' => [['email' => $email]],
            'htmlContent' => $htmlContent
        ]);

        try {
            $apiInstance->sendTransacEmail($emailObj);
            $request->session()->forget([
                self::REG_OTP_VERIFIED_EMAIL_KEY,
                self::REG_OTP_VERIFIED_AT_KEY,
            ]);
            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }

    public function regauthcheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $email = strtolower(trim((string) $request->email));
        $record = DB::table('password_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json(0);
        }

        DB::table('password_otps')
            ->where('email', $email)
            ->delete();

        $request->session()->put(self::REG_OTP_VERIFIED_EMAIL_KEY, $email);
        $request->session()->put(self::REG_OTP_VERIFIED_AT_KEY, now()->toDateTimeString());

        return response()->json(1);
    }

    public function forgotauthsend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = random_int(100000, 999999);
        $email = $request->email;
        $htmlContent = view('components.emails.password_reset', [
            'otp' => $otp,
            'expiryMinutes' => 3,
        ])->render();

        $exist = DB::table('users')
            ->where('email', $email)
            ->first();

        if ($exist == null) {
            return response()->json(0);
        }

        DB::table('password_otps')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(3),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('services.brevo.key'));

        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

        $emailObj = new SendSmtpEmail([
            'subject' => 'RAN Lending Password Reset OTP',
            'sender' => ['name' => 'RAN Lending', 'email' => 'lordanniel@gmail.com'],
            'to' => [['email' => $email]],
            'htmlContent' => $htmlContent
        ]);

        try {
            $apiInstance->sendTransacEmail($emailObj);
            return response()->json(1);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }

    public function forgotauthcheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json(0);
        }

        return response()->json(1);
    }

    public function forgotchangepass(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'pass' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json(0);
        }

        $updated = DB::table('users')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->pass),
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json(0);
        }

        DB::table('password_otps')
            ->where('email', $request->email)
            ->delete();

        return response()->json(1);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6'
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        $token = Password::createToken(User::where('email', $request->email)->first());

        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email]);
    }
}
