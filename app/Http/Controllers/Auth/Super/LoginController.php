<?php

namespace App\Http\Controllers\Auth\Super;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function showLoginForm()
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
        return redirect('/super/dashboard');
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
