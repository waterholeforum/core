<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use Waterhole\Models\Post;

class Composer extends Component
{
    public Post $post;
    public ?ViewErrorBag $errors;
    public bool $open;

    public function __construct(Post $post, ViewErrorBag $errors = null, bool $open = false)
    {
        $this->post = $post;
        $this->errors = $errors;
        $this->open = $open;
    }

    public function render()
    {
        return view('waterhole::components.composer');
    }
}
