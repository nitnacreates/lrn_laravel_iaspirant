<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SubjectFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        // same as in provider
        return 'subject_helper';
    }
}
