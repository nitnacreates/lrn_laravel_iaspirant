<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Facades\Helper\CronHelper;

class CronServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cron_helper',function(){
            return new CronHelper;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
