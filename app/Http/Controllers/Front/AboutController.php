<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'about');
        view()->share('pagetitle', 'Home');
        $this->request = $request;
    }

    public function subscription()
    {
        if (Auth::check()) {
            return redirect()->route('user::subscription', ['action' => 'show']);
        }
        
        return view('user.subscription.show');
    }
}
