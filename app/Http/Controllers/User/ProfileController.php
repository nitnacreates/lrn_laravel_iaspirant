<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'profile');
        view()->share('pagetitle', 'User Profile');
        $this->request = $request;
    }

    public function show(){
        return view('user.profile.show');
    }
}
