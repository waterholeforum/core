<?php

declare(strict_types=1);

namespace Waterhole\Actions;

use Illuminate\Support\Collection;

class EditTag extends Edit
{
    public function attributes(Collection $models): array
    {
        return ['data-turbo-frame' => 'modal'];
    }
}
