<?php

namespace Waterhole\Actions;

use Waterhole\Models\Model;

class DeleteStructure extends Delete
{
    protected function resource(Model $model): string
    {
        return 'structure';
    }
}
