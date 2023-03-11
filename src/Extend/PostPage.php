<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostAnswer;

/**
 * A list of components to render on the post page, between the post and the
 * comments.
 */
abstract class PostPage
{
    use OrderedList, OfComponents;
}

PostPage::add(PostAnswer::class, key: 'answer');
