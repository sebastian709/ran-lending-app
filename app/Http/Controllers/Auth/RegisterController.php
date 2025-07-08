<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserIncome;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //MOVE VALIDATION VIA JS
        // dd($data);
        $this->create($data);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data);

        $user = User::create([
            'firstname'            => $data['firstname'],
            'lastname'             => $data['lastname'],
            'middlename'           => $data['middlename'],
            'contactno'            => $data['contactnumber'],
            'is_referral'          => $data['referral_source'],
            'referral_source_id'   => $data['referral_names'],
            'email'                => $data['email'],
            'password'             => Hash::make($data['password']),
        ]);

        // dd($user->id);

        UserDetails::create([
            'user_id'   => $user->id,
            'house_no'  => $data['house_no'],
            'street'    => $data['street'] ,
            'barangay'  => $data['barangay'] ,
            'city'      => $data['city'] ,
            'province'  => $data['province'] ,
        ]);

        UserIncome::create([
            'user_id' => $user->id ,
            'occupation' => $data['occupation'] ,
            'income' => $data['income'] ,
            'employment_status' => $data['employment_status'] ,
        ]);


        // dd($data);
        return 1;
    }
}
