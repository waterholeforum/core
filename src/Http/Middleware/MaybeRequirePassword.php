<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Http\Request;

use function Waterhole\internal_url;

/**
 * Middleware to require password confirmation, but only if the user's account
 * has a password set.
 */
class MaybeRequirePassword
{
    public function __construct(
        private RequirePassword $middleware,
    ) {}

    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->password) {
            return $next($request);
        }

        $response = $this->middleware->handle($request, $next, 'waterhole.confirm-password');

        if (!$response->isRedirect(route('waterhole.confirm-password'))) {
            return $response;
        }

        $intended = redirect()->getIntendedUrl();
        $return = internal_url($request->query('return'), url()->previous());

        if ($intended && $return) {
            redirect()->setIntendedUrl(url()->query($intended, [
                'return' => $return,
            ]));
        }

        return $response;
    }
}
