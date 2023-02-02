<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\PostBody;
use Waterhole\Forms\Fields\PostTitle;

abstract class PostForm
{
    use OrderedList, OfComponents;
}

PostForm::add(PostTitle::class, position: -20, key: 'title');
PostForm::add(PostBody::class, position: -10, key: 'body');
