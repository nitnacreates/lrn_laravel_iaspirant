<?php

namespace App\Facades;
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

    public function countdownto24($created_at)
    {
        $response_carb = Carbon::createFromFormat('Y-m-d H:i:s', $created_at);
        $now = Carbon::now();
        $more_than_day = $now->diffInDays($response_carb->copy()); 
        if($more_than_day){
            return false;
        } else{
            $time_left = $response_carb->add('1 day')->diff($now)->format('%H:%I:%S');
            return ($time_left == "00:00:00") ? "23:59:59" : $time_left;
        }
    }
}
