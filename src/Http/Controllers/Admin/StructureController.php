<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureLink;

class StructureController extends Controller
{
    public function index()
    {
        $structure = Structure::with(['content' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Channel::class => ['permissions.recipient'],
                Page::class => ['permissions.recipient'],
                StructureLink::class => ['permissions.recipient'],
            ]);
        }])
            ->orderBy('position')
            ->get();

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
