<?php

namespace Waterhole\Http\Controllers\Admin;

use Waterhole\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('waterhole::admin.dashboard');
    }

    public function widget(int $id)
    {
        abort_unless($widget = config('waterhole.admin.widgets.'.$id), 404);

        return view('waterhole::admin.widget', compact('id', 'widget'));
    }
}
