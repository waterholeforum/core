<?php

namespace Waterhole\View\Components;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\View\Components\Concerns\Streamable;

class PostSidebar extends Component
{
    use Streamable;

    public bool|Response $response;

    public function __construct(public Post $post)
    {
        $gate = Gate::forUser(Auth::user() ?: new User());

        $this->response = $gate->inspect('post.comment', $post);
    }

    public function render()
    {
        return $this->view('waterhole::components.post-sidebar');
    }
}
