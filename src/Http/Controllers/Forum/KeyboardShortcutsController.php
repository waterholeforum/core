<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Support\Collection;
use Waterhole\Extend\Ui\KeyboardShortcuts;
use Waterhole\Http\Controllers\Controller;

class KeyboardShortcutsController extends Controller
{
    public function __invoke()
    {
        $shortcuts = collect(resolve(KeyboardShortcuts::class)->shortcuts())
            ->groupBy('category')
            ->map(fn(Collection $group) => $group->values());

        return view('waterhole::forum.keyboard-shortcuts', compact('shortcuts'));
    }
}
