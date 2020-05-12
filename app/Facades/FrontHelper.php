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
}
