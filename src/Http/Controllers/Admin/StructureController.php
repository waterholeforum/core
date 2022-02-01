<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Structure;

/**
 * Controller for the admin structure index.
 */
class StructureController extends Controller
{
    public function index()
    {
        $structure = Structure::query()
            ->orderBy('position')
            ->get()
            ->loadMissing('content.permissions.recipient');

        return view('waterhole::admin.structure.index', compact('structure'));
    }

    public function saveOrder(Request $request)
    {
        $request['order'] = json_decode($request->input('order'), true);

        $data = $request->validate([
            'order' => 'array',
            'order.*' => 'integer',
        ]);

        if ($data['order']) {
            foreach ($data['order'] as $position => $node) {
                Structure::whereKey($node)->update(compact('position'));
            }
        }

        return redirect()->route('waterhole.admin.structure');
    }
}
