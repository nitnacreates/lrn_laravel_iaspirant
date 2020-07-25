<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Storage;

class Kernel extends ConsoleKernel
{
    protected function scheduleTimezone()
    {
        return 'Asia/Kolkata';
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // prelim daily
        $schedule->call(function () {
            \CronHelper::unlockPrelim();
        })->dailyAt('00:05');

        // clean up subscription table
        $schedule->call(function () {
            \CronHelper::authorizeUserSubscription();
        })->dailyAt('00:05');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
