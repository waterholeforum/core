<?php

declare(strict_types=1);

namespace Waterhole\Actions;

use Waterhole\Models\Model;

class EditStructure extends Edit
{
    protected function resource(Model $model): string
    {
        return 'structure';
    }
}
