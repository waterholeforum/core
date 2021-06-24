<?php

namespace Waterhole\Http\Middleware\Admin;

use Closure;
use Waterhole\Licensing\Outpost;

class ContactOutpost
{
    public function __construct(private Outpost $outpost)
    {
    }

    public function handle($request, Closure $next)
    {
        $this->outpost->contact();

        return $next($request);
    }
}
