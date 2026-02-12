<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Post;

class PostDraft extends Component
{
    public function __construct(public Post $post) {}

    public function shouldRender(): bool
    {
        return Auth::check() && $this->post->userState?->hasDraft();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-draft');
    }
}
