<?php

namespace App\Http\Controllers\Auth\Super;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Model\Account;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    protected $redirectTo = '/super/dashboard';

    public function showLoginForm(Request $request)
    {
        view()->share('page', 'login-page');
        view()->share('pagetitle', 'Login');
        
        return view('auth.super.login');
    }

    public function username()
    {
        return 'name';
    }

    protected function authenticated(Request $request, $user)
    {
        if($user_id = $user->id){
            $account = Account::select(['id'])->where('all_id',$user_id)->where('user_type','s')->first();
            if(!empty($account)){
                $request->session()->put('account_id', $account->id);        
            }
        }
        return redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/super/login');
    }

    protected function guard()
    {
        return \Auth::guard('superg');
    }

}
