<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthGuard
{
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse(config('waterhole.auth.guard', 'web'));

        return $next($request);
    }
}
