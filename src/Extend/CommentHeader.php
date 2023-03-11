<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\CommentAnswerBadge;

/**
 * A list of components to render in each comment's header.
 */
abstract class CommentHeader
{
    use OrderedList;
}

CommentHeader::add(CommentAnswerBadge::class, key: 'answer');
