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
            (!config('waterhole.forum.public', true) ||
                (!$this->permissions->can(null, 'view', Channel::class) &&
                    !$this->permissions->can(null, 'view', Page::class) &&
                    !$this->permissions->can(null, 'view', StructureLink::class)))
        ) {
            return redirect()->route('waterhole.login');
        }

        return $next($request);
    }
}
