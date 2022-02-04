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
        $structure = Structure::with('content')
            ->orderBy('position')
            ->get();

        return view('waterhole::admin.structure.index', compact('structure'));
    }

    public function saveOrder(Request $request)
    {
        $request['order'] = json_decode($request->input('order'), true);

        $data = $request->validate([
            'order' => 'array',
            'order.*' => 'array:id,listIndex',
        ]);

        if ($data['order']) {
            foreach ($data['order'] as $position => $node) {
                Structure::whereKey($node['id'])->update([
                    'position' => $position,
                    'is_listed' => ! $node['listIndex'],
                ]);
            }
        }

        return redirect()->route('waterhole.admin.structure');
    }
}
