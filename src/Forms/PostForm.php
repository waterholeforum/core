<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\Post;

class PostForm extends Form
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function fields(): array
    {
        return Extend\PostForm::components(['model' => $this->model]);
    }
}
