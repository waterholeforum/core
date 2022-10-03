<?php

namespace Waterhole\Http\Controllers\Admin;

use Waterhole\Http\Controllers\Controller;

class LicenseController extends Controller
{
    public function __invoke()
    {
        return view('waterhole::admin.license');
    }
}
