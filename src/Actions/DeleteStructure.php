<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Model;

class DeleteStructure extends Delete
{
    public function confirm(Collection $models): string
    {
        $message = parent::confirm($models);
        $node = $models[0]->structure()->firstOrFail();

        return $node->children()->withoutGlobalScopes()->exists()
            ? $message . ' ' . __('waterhole::cp.delete-structure-children-promoted-message')
            : $message;
    }

    public function run(Collection $models)
    {
        $models->each(function (Model $model) {
            DB::transaction(function () use ($model) {
                $node = $model->structure()->firstOrFail();
                $node->promoteChildren();
                $model->delete();
            });
        });
    }

    protected function resource(Model $model): string
    {
        return 'structure';
    }
}
