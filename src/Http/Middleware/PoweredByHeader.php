<?php

namespace Waterhole\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PoweredByHeader
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('waterhole.system.send_powered_by_header') && $response instanceof Response) {
            $response->header('X-Powered-By', 'Waterhole');
        }

        return $response;
    }
}
