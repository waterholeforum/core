<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Structure;

/**
 * Controller for the CP structure index.
 */
class StructureController extends Controller
{
    public function index()
    {
        $structure = Structure::flattenTree(
            Structure::withoutGlobalScopes()
                ->tree()
                ->with(['content', 'permissions.recipient'])
                ->inSiblingOrder()
                ->get()
                ->toTree(),
        );

        return view('waterhole::cp.structure.index', compact('structure'));
    }

    public function saveOrder(Request $request)
    {
        $request['order'] = json_decode($request->input('order'), true);

        $data = $request->validate([
            'order' => ['array'],
            'order.*.id' => ['required', 'integer', Rule::exists('structure', 'id')],
            'order.*.parent_id' => ['nullable', 'integer', Rule::exists('structure', 'id')],
            'order.*.position' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['order'] as $move) {
                Structure::withoutGlobalScopes()
                    ->findOrFail($move['id'])
                    ->update([
                        'parent_id' => $move['parent_id'],
                        'position' => $move['position'],
                    ]);
            }
        });

        return redirect()->route('waterhole.cp.structure');
    }
}
