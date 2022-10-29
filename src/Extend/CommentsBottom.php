<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\CommentsLocked;

/**
 * A list of components to render at the bottom of the last page of comments.
 */
abstract class CommentsBottom
{
    use OrderedList;
}

CommentsBottom::add('locked', CommentsLocked::class);
