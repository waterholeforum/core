<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Http\Request;

/**
 * Middleware to require password confirmation, but only if the user's account
 * has a password set.
 */
class MaybeRequirePassword
{
    public function __construct(private RequirePassword $middleware)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->password) {
            return $this->middleware->handle($request, $next, 'waterhole.confirm-password');
        }

        return $next($request);
    }
}
