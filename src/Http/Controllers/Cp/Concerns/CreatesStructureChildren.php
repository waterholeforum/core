<?php

namespace Waterhole\Http\Controllers\Cp\Concerns;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Waterhole\Models\Structure;

trait CreatesStructureChildren
{
    protected function structureParent(Request $request): ?Structure
    {
        if (!$request->filled('parent_id')) {
            return null;
        }

        $data = $request->validate(['parent_id' => ['integer']]);
        $parent = Structure::withoutGlobalScopes()->find($data['parent_id']);

        if (!$parent?->canHaveChildren()) {
            throw ValidationException::withMessages([
                'parent_id' => __('validation.exists', ['attribute' => 'parent']),
            ]);
        }

        return $parent;
    }

    protected function appendToStructureParent(Structure $node, ?Structure $parent): void
    {
        if (!$parent) {
            return;
        }

        $node->update([
            'parent_id' => $parent->getKey(),
            'position' => Structure::nextPosition($parent->getKey()),
        ]);
    }
}
