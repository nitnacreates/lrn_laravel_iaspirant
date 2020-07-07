<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ManifestController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'manifest');
        view()->share('pagetitle', 'About');
        $this->request = $request;
    }

    public function subscription()
    {
        if (Auth::check()) {
            return redirect()->route('user::subscription', ['action' => 'show']);
        }
        
        return view('user.subscription.show');
    }

    public function about_us()
    {
        $view_arr['pagetitle'] = 'About';   
        return view('front.manifest.about_us')->with($view_arr);
    }

    public function contact()
    {   
        $view_arr['pagetitle'] = 'Contact Us';
        return view('front.manifest.contact')->with($view_arr);
    }
}
