<?php

namespace Waterhole\Forms;

use Waterhole\Models\Post;

class PostForm extends Form
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\PostForm::class)->components(['model' => $this->model]);
    }
}
