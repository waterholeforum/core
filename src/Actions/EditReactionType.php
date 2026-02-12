<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;

class EditReactionType extends Edit
{
    public function attributes(Collection $models): array
    {
        return ['data-turbo-frame' => 'modal'];
    }
}
