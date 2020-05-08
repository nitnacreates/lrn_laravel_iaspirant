<?php

namespace App\Facades;

use Illuminate\Support\Facades\Auth;

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

    public function getAccountId()
    {
        return Request::session()->get('account_id'); 
    }

}
