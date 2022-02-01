<?php

namespace Waterhole\Http\Middleware\Admin;

use Closure;
use Waterhole\Licensing\Outpost;

class ContactOutpost
{
    protected Outpost $outpost;

    public function __construct(Outpost $outpost)
    {
        $this->outpost = $outpost;
    }

    public function handle($request, Closure $next)
    {
        $this->outpost->radio();

        return $next($request);
    }
}
