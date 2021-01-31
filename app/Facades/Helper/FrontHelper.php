<?php

namespace App\Facades\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

class FrontHelper
{
    public function checkAuthId($user_id)
    {
        return Auth::id() == $user_id;
    }

    public function transformDate($from_format, $to_format, $date_input)
    {
        $dt = \DateTime::createFromFormat($from_format, $date_input);
        return $dt->format($to_format);
    }

    public function checkRoute($path)
    {
        $path_request = Request::create($path);
        $routes = collect(\Route::getRoutes())->map(function ($route) use($path_request, $path){
            return $route->matches($path_request);
        })->all();

        $counter_exist = 0;
        foreach ($routes as $route_bool) {
            if($route_bool) $counter_exist++;
        }

        return $counter_exist > 1;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function countdownto24($created_at)
    {
        $response_carb = Carbon::createFromFormat('Y-m-d H:i:s', $created_at);
        $now = Carbon::now();
        $more_than_day = $now->diffInDays($response_carb->copy(), false); 
        if($more_than_day){
            return false;
        } else{
            $time_left = $response_carb->add('1 day')->diff($now)->format('%H:%I:%S');
            return ($time_left == "00:00:00") ? "23:59:59" : $time_left;
        }
    }

    public function daysFromToday($date_to)
    {
        $days_to_date = Carbon::createFromFormat('Y-m-d H:i:s', $date_to);
        $now = Carbon::now();
        $more_than_day = $now->diffInDays($days_to_date, false); 
        
        return $more_than_day > 0 ? $more_than_day : 0;
    }

    public function testSeriesTimeLeft($mode)
    {
        $now = Carbon::now();
        switch ($mode) {
            case '1': // prelims test series
                $to_date = Carbon::createFromFormat('Y-m-d', "2021-07-05");
                $rem_days = $now->diffInDays($to_date, false); 
                return $rem_days . " days";
                break;
            case '2': // prelims test series
                $to_date = Carbon::createFromFormat('Y-m-d', "2021-07-05");
                $rem_days = $now->diffInMonths($to_date, false); 
                return $rem_days . " months";
                break;
            case '3': // mains test series
                $to_date = Carbon::createFromFormat('Y-m-d', "2021-11-01");
                $rem_days = $now->diffInMonths($to_date, false); 
                return $rem_days . " months";
                break;
            default:
                return "22 months";    
            break;
        }
    }

    public function examTimeLeft($mode)
    {
        $now = Carbon::now();
        switch ($mode) {
            case '1': // prelims
                $to_date = Carbon::createFromFormat('Y-m-d', "2022-07-03");
                $rem_days = $now->diffInDays($to_date, false); 
                return $rem_days . " days";
                break;
            case '2': // prelims
                $to_date = Carbon::createFromFormat('Y-m-d', "2022-07-03");
                $rem_days = $now->diffInMonths($to_date, false); 
                return $rem_days . " months";
                break;
            case '3': // mains
                $to_date = Carbon::createFromFormat('Y-m-d', "2022-09-28");
                $rem_days = $now->diffInMonths($to_date, false); 
                return $rem_days . " months";
                break;
            default:
                return "22 months";    
            break;
        }
    }
}
