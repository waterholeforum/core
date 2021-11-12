<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActorSeen
{
    /**
     * Save the actor's "last seen" time so that we can use it to show
     * them what content is new since their last visit. Then update it.
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            ($actor = $request->user())
            && (
                ! $request->session()->has('previously_seen_at')
                || $request->has('update_previously_seen_at')
            )
        ) {
            $request->session()->put('previously_seen_at', $actor->last_seen_at);
        }

        return $next($request);
    }

    public function terminate(Request $request)
    {
        $request->user()?->update(['last_seen_at' => now()]);
    }
}
