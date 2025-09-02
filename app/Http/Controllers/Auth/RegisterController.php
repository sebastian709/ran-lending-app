<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserDetails;
use App\Models\UserIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Carbon;



class RegisterController extends Controller
{
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
        $user = User::create([
            'firstname'           => $request->firstname,
            'lastname'            => $request->lastname,
            'middlename'          => $request->middlename,
            'contactno'           => $request->contactnumber,
            'is_referral'         => $request->referral_source,
            'referral_source_id'  => $request->referral_names,
            'email'               => $request->email,
            'emai_verified_at'    => now(),
            'password'            => Hash::make($request->password),
        ]);

        UserDetails::create([
            'user_id'  => $user->id,
            'house_no' => $request->house_no,
            'street'   => $request->street,
            'barangay' => $request->barangay,
            'city'     => $request->city,
            'province' => $request->province,
        ]);

        UserIncome::create([
            'user_id'           => $user->id,
            'occupation'        => $request->occupation,
            'income'            => $request->income,
            'employment_status' => $request->employment_status,
            'specified_others' => $request->specify_others
        ]);

        
    //     $otp = random_int(100000, 999999);
    //     $email = $request->email;
    
    //     // Store OTP
    //     DB::table('password_otps')->updateOrInsert(
    //         ['email' => $email],
    //         [
    //             'otp' => $otp,
    //             'expires_at' => Carbon::now()->addMinutes(10),
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]
    //     );
    //     $config = Configuration::getDefaultConfiguration()
    //     ->setApiKey('api-key', env('BREVO_API_KEY'));
    
    // $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
    
    // $emailObj = new SendSmtpEmail([
    //     'subject' => '✅ Complete Your Registration - OTP Inside',
    //     'sender' => ['name' => 'Ran Serenity', 'email' => 'lordanniel@gmail.com'],
    //     'to' => [['email' => $email]],
    //     'htmlContent' => "
    //         <div style='font-family: Arial, sans-serif; color: #333; padding: 20px; max-width: 600px;'>
    //             <h2 style='color: #4A90E2;'>Welcome to Ran Serenity!</h2>
    //             <p>To complete your registration, please use the verification code below:</p>
    //             <p style='font-size: 28px; font-weight: bold; color: #4A90E2; letter-spacing: 2px;'>$otp</p>
    //             <p>This code will expire in <strong>10 minutes</strong>, so please enter it promptly.</p>
    //             <p>If you didn’t request this registration, you can safely ignore this email.</p>
    //             <br>
    //             <p>Thank you,<br>The Ran Serenity </p>
    //         </div>
    //     ",
    // ]);
    
    // $apiInstance->sendTransacEmail($emailObj);
    
    // try {
    //     $apiInstance->sendTransacEmail($emailObj);
    // } catch (\Exception $e) {
    //     return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
    // }
        return back()->with('success', 'Registration successful! Please log in.');
    }
}
