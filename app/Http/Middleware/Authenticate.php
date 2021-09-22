<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next)
    {
        if (!Auth::guard('api')->check())
            return response('you must be logged in', 401);
        Config::set('auth_user', Auth::guard('api')->user());
        return $next($request);
    }
}
