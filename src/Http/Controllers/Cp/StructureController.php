<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Structure;

/**
 * Controller for the CP structure index.
 */
class StructureController extends Controller
{
    public function index()
    {
        $structure = Structure::with('content')
            ->orderBy('position')
            ->get();

        return view('waterhole::cp.structure.index', compact('structure'));
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
                    'is_listed' => !$node['listIndex'],
                ]);
            }
        }

        return redirect()->route('waterhole.cp.structure');
    }
}
