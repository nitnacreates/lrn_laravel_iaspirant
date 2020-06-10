<?php

namespace App\Facades\Helper;
use Illuminate\Support\Carbon;

use App\Model\Prelim\PrelimRoster;

// alias is CronHelper
class CronHelper
{
    public function unlockPrelim()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        PrelimRoster::where('locked', 1)
                        ->where('available_from', $today)
                        ->update(['locked' => 0]);
        return true;
    }

}
