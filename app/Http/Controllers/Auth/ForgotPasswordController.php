<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
{
    // $request->validate([
    //     'email' => 'required|email|exists:users,email',
    // ]);
    // Generate OTP
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
    'subject' => 'Your OTP Code',
    'sender' => ['name' => 'YourApp', 'email' => 'lordanniel@gmail.com'],
    'to' => [['email' => $email]],
    'htmlContent' => "<p>Your OTP is <strong>$otp</strong>. It will expire in 10 minutes.</p>",
]);


$apiInstance->sendTransacEmail($emailObj);

try {
    $apiInstance->sendTransacEmail($emailObj);
} catch (\Exception $e) {
    return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
}


    return back()->with('status', 'OTP sent to your email');
}

    use SendsPasswordResetEmails;
}
