<?php

namespace Waterhole\Http\Controllers\Admin;

use Waterhole\Http\Controllers\Controller;

/**
 * Controller for admin dashboard.
 */
class DashboardController extends Controller
{
    public function index()
    {
        return view('waterhole::admin.dashboard');
    }

    /**
     * Render a widget by its position in the widget configuration.
     *
     * This is used for lazily-loaded widgets. Instead of rendering these
     * widgets straight into the dashboard view, they are lazily-loaded in a
     * Turbo Frame pointing to this route.
     */
    public function widget(int $id)
    {
        abort_unless($widget = config('waterhole.admin.widgets.' . $id), 404);

        return view('waterhole::admin.widget', compact('id', 'widget'));
    }
}
