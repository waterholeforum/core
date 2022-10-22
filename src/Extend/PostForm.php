<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\PostBody;
use Waterhole\Forms\Fields\PostChannel;
use Waterhole\Forms\Fields\PostTitle;

abstract class PostForm
{
    use OrderedList, OfComponents;
}

PostForm::add('channel', fn($post) => !$post->exists ? PostChannel::class : null, position: -30);
PostForm::add('title', PostTitle::class, position: -20);
PostForm::add('body', PostBody::class, position: -10);
