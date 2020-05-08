<?php

namespace App\Facades;
use Illuminate\Support\Facades\Auth;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

class FrontHelper
{
    public function checkAuthId($user_id)
    {
        return Auth::id() == $user_id;
    }
}
