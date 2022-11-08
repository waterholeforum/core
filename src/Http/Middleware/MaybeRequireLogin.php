<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\PermissionCollection;

/**
 * Middleware to require guests to log in if there are no structure items
 * visible to the public.
 */
class MaybeRequireLogin
{
    public function __construct(protected PermissionCollection $permissions)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest() && !$this->permissions->guest()->isNotEmpty()) {
            return redirect()->route('waterhole.login');
        }

        return $next($request);
    }
}
