<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to save the actor's "last seen" time so that we can use it to show
 * them what content is new since their last visit.
 */
class ActorSeen
{
    public function handle(Request $request, Closure $next)
    {
        if ($actor = $request->user()) {
            $request->session()->put('previously_seen_at', $actor->last_seen_at);
        }

        return $next($request);
    }

    public function terminate(Request $request): void
    {
        if (($actor = $request->user()) && $actor->last_seen_at < now()->subMinutes(1)) {
            $actor->update(['last_seen_at' => now()]);
        }
    }
}
