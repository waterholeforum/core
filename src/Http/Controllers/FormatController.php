<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatController extends Controller
{
    public function __invoke(Request $request)
    {
        $formatter = app('waterhole.formatter');

        $xml = $formatter->parse((string) $request->getContent());

        return $formatter->render($xml, ['actor' => Auth::user()]);
    }
}
