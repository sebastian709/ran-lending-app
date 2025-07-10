<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use GuzzleHttp\Client as GuzzleClient;

class OtpVerificationController extends Controller
{
    public function showForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function regauthsend(Request $request){
        $otp = random_int(100000, 999999);
        $email = $request->email;
    
        // Store OTP
        DB::table('password_otps')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        $config = Configuration::getDefaultConfiguration()
        ->setApiKey('api-key', env('BREVO_API_KEY'));
    
        $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
        
        $emailObj = new SendSmtpEmail([
            'subject' => '✅ Complete Your Registration - OTP Inside',
            'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
            'to' => [['email' => $email]],
            'htmlContent' => "
                <div style='font-family: Arial, sans-serif; color: #333; padding: 20px; max-width: 600px;'>
                    <h2 style='color: #4A90E2;'>Welcome to Ran Serenity!</h2>
                    <p>To complete your registration, please use the verification code below:</p>
                    <p style='font-size: 28px; font-weight: bold; color: #4A90E2; letter-spacing: 2px;'>$otp</p>
                    <p>This code will expire in <strong>10 minutes</strong>, so please enter it promptly.</p>
                    <p>If you didn’t request this registration, you can safely ignore this email.</p>
                    <br>
                    <p>Thank you,<br>The Ran Serenity </p>
                </div>
            ",
        ]);
        
        // $apiInstance->sendTransacEmail($emailObj);
        
        try {
            $apiInstance->sendTransacEmail($emailObj);
            return response()->json(1);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }
    
    public function regauthcheck(Request $request){
        // dd($request->all);
        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json(0);
        }else{
            return response()->json(1);

        }

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
