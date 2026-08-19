<?php

namespace Waterhole\Forms;

use Waterhole\Forms\Fields\PostTags;
use Waterhole\Models\Post;

class PostTagsForm extends Form
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function fields(): array
    {
        return [new PostTags($this->model)];
    }
}
