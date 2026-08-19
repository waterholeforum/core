<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;

/**
 * Middleware to require guests to log in if there are no structure items
 * visible to the public.
 */
class MaybeRequireLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::guest()
            && !Structure::where(
                'content_type',
                '!=',
                (new StructureHeading())->getMorphClass(),
            )->exists()
        ) {
            return redirect()->route('waterhole.login');
        }

        return $next($request);
    }
}
