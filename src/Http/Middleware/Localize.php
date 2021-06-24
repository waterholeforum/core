<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Waterhole\Extend\Locales;

class Localize
{
    public const SESSION_KEY = 'locale';

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $locales = Locales::keys();

        // Allow the locale to be set in a query parameter. If there is a
        // logged-in user, update their preference in the database; otherwise,
        // store the preference in the session.
        if (in_array($locale = $request->query('locale'), $locales)) {
            if ($user) {
                $user->update(['locale' => $locale]);
            } else {
                session()->put(static::SESSION_KEY, $locale);
            }

            return back();
        }

        // Retrieve the user's locale preference, either from the user model if
        // logged in, or from the session or browser preference otherwise.
        if ($user) {
            $locale = $user->preferredLocale();
        } else {
            $locale = session(static::SESSION_KEY, $request->getPreferredLanguage($locales));
        }

        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
