<?php

namespace Waterhole\Http\Controllers\Cp;

use Waterhole\Http\Controllers\Controller;

/**
 * Controller for CP dashboard.
 */
class DashboardController extends Controller
{
    public function index()
    {
        return view('waterhole::cp.dashboard');
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
        abort_unless($widget = config('waterhole.cp.widgets.' . $id), 404);

        return view('waterhole::cp.widget', compact('id', 'widget'));
    }
}
