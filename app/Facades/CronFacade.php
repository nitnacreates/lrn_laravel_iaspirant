<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CronFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cron_helper';
    }
}
