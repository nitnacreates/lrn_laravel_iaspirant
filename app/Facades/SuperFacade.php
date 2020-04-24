<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SuperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        // same as in provider
        if(\Auth::guard('superg')->check()){
            return 'super_helper';    
        }
        return false;
    }
}
