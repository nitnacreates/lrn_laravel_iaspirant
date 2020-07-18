<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class CheckPrelim
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_sub = Auth::user()->subscription;
        if(Auth::check() && in_array($user_sub, [10,20])){
            return $next($request);
        }
        abort(403, 'Sorry, your subscription doesnt allow prelim section');
    }
}
