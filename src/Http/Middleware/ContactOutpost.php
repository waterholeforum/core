<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Waterhole\Licensing\Outpost;

class ContactOutpost
{
    public function __construct(private Outpost $outpost)
    {
    }

    public function handle($request, Closure $next)
    {
        if (Gate::allows('administrate')) {
            $this->outpost->contact();
        }

        return $next($request);
    }
}
