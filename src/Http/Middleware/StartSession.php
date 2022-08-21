<?php

namespace Waterhole\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession as Middleware;

class StartSession extends Middleware
{
    protected function storeCurrentUrl(Request $request, $session)
    {
        if (!$request->headers->has('Turbo-Frame')) {
            parent::storeCurrentUrl($request, $session);
        }
    }
}
