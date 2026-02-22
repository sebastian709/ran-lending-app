<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:6,1')->only('sendResetLinkEmail');
    }

    public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $otp = random_int(100000, 999999);
    $email = $request->email;

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
    ->setApiKey('api-key', config('services.brevo.key'));

$apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);

$emailObj = new SendSmtpEmail([
    'subject' => 'Your OTP Code',
    'sender' => ['name' => 'YourApp', 'email' => 'lordanniel@gmail.com'],
    'to' => [['email' => $email]],
    'htmlContent' => "<p>Your OTP is <strong>$otp</strong>. It will expire in 10 minutes.</p>",
]);

try {
    $apiInstance->sendTransacEmail($emailObj);
} catch (\Exception $e) {
    return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
}


    return back()->with('status', 'OTP sent to your email');
}

    use SendsPasswordResetEmails;
}
