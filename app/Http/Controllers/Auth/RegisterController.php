<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Model\User\UserKeep;
use App\Model\Account;

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
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->share('page', 'user_registeration');
        view()->share('pagetitle', 'User Registration');
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
        return Validator::make($data, [
            'first_name' => 'required|alpha|min:2',
            'last_name' => 'required|alpha|min:2',
            'name' => ['required', 'min:6', 'max:255', 'regex:/^[a-z\._]+$/', 'unique:users'],
            'email' => ['required', 'email', 'regex:/^[A-Za-z0-9-\._]+@(gmail.com|yahoo.co.in|yahoo.co.in|yahoo.com|rediff.com|rediff.in|rediffmail.com|zoho.com|zoho.in|outlook.com|live.com)$/', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ],[
            'name.regex' => 'Only alphabet, period and underscore allowed',
            'email.regex' => 'Enter a valid domain name'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'subscription' => '1'         // free subscription
        ]);

        $user_keep = new UserKeep;
        $user_keep->user_id = $user->id;
        $user_keep->first_name = $data['first_name'];
        $user_keep->last_name = $data['last_name'];
        $user_keep->phone_validated = 0;
        $user_keep->save();

        $account = new Account;
        $account->all_id = $user->id;
        $account->user_type = 'n';
        $account->save();

        return $user;
    }
}
