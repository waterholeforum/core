<?php

namespace Waterhole\Formatter;

use Waterhole\Models\Model;
use Waterhole\Models\User;

class Context
{
    public function __construct(
        public ?Model $model = null,
        public ?User $user = null,
    ) {
    }
}
