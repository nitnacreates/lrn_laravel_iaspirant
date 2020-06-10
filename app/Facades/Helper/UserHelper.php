<?php

namespace App\Facades\Helper;

use Illuminate\Support\Facades\Auth;
use App\User;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

/* 
* ONLY for logged in users
*/
class UserHelper
{

    public function getUserId()
    {
        // $user = Auth::user();
        return Auth::id(); 
    }

    public function getUserName()
    {
        return Auth::user()->name; 
    }

    public function getAccountId()
    {
        return Request::session()->get('account_id'); 
    }

    public function getUserAccount($user_name)
    {
        $user_exists = User::where('name',$user_name)->first();
        return empty($user_exists) ? 0 : $user_exists->id;
    }
}
