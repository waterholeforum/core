<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localize
{
    public function handle(Request $request, Closure $next)
    {
        // TODO: detect locale from the browser's preferred
        // See https://laracasts.com/discuss/channels/laravel/what-is-the-best-way-to-set-language-for-user

        if ($actor = Auth::user()) {
            if ($locale = $actor->preferredLocale()) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
