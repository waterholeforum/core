<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Structure;

class StructureController extends Controller
{
    public function __invoke()
    {
        $structure = Structure::with('content')
            ->tree()
            ->orderBy('position')
            ->get()
            ->toTree();

        return view('waterhole::admin.structure', compact('structure'));
    }

    public function save(Request $request)
    {
        $request['order'] = json_decode($request->input('order'), true);

        $data = $request->validate([
            'order' => 'array',
            'order.*' => 'array:id,position,parent_id',
            'order.*.id' => 'required|integer',
            'order.*.position' => 'required|integer|min:0',
            'order.*.parent_id' => 'nullable|integer|exists:structure,id',
        ]);

        if ($data['order']) {
            foreach ($data['order'] as $node) {
                Structure::whereKey($node['id'])->update([
                    'position' => $node['position'],
                    'parent_id' => $node['parent_id'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('waterhole.admin.structure')
            ->with('success', 'Structure saved.');
    }
}
