<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\PermissionCollection;
use Waterhole\Models\StructureLink;

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
        if (
            Auth::guest() &&
            $this->permissions
                ->guest()
                ->whereIn('scope_type', [
                    (new Channel())->getMorphClass(),
                    (new Page())->getMorphClass(),
                    (new StructureLink())->getMorphClass(),
                ])
                ->ability('view')
                ->isEmpty()
        ) {
            return redirect()->route('waterhole.login');
        }

        return $next($request);
    }
}
