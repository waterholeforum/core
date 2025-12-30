<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthGuard
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        Auth::shouldUse($guard ?? config('waterhole.auth.guard', 'web'));

        return $next($request);
    }
}
