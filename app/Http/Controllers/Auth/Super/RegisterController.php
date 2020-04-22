<?php

namespace App\Http\Controllers\Auth\Super;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Model\Super\SuperUser;
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
    protected $redirectTo = '/super/dashboard';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        view()->share('page', 'register-page');
        view()->share('pagetitle', 'Register');
        return view('auth.super.register');
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
            'name' => ['required', 'string', 'max:255', 'unique:super_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function guard()
    {
        return \Auth::guard('superg');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $super_user = SuperUser::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);

        $account = new Account;
        $account->all_id = $super_user->id;
        $account->user_type = 's';
        $account->save();

        return $super_user;
    }
}
