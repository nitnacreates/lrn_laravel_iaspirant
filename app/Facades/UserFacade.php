<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        // same as in provider
        if(\Auth::guard()->check()){
            return 'user_helper';    
        }
        return false;
    }
}
