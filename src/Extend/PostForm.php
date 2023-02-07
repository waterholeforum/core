<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\PostBody;
use Waterhole\Forms\Fields\PostTags;
use Waterhole\Forms\Fields\PostTitle;

abstract class PostForm
{
    use OrderedList, OfComponents;
}

PostForm::add(PostTitle::class, position: -100, key: 'title');
PostForm::add(PostTags::class, position: -90, key: 'tags');
PostForm::add(PostBody::class, position: -80, key: 'body');
