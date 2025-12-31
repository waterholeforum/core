<?php

namespace Waterhole\Extend\Forms;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\Forms\Fields\PostBody;
use Waterhole\Forms\Fields\PostTags;
use Waterhole\Forms\Fields\PostTitle;

/**
 * List of fields for the post create/edit form.
 *
 * Use this extender to add, remove, or reorder fields when building the form.
 */
class PostForm extends ComponentList
{
    public function __construct()
    {
        $this->add(PostTitle::class, 'title');
        $this->add(PostTags::class, 'tags');
        $this->add(PostBody::class, 'body');
    }
}
