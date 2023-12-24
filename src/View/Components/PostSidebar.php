<?php

namespace Waterhole\View\Components;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
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
        $gate = Gate::forUser(Auth::user() ?: (Route::has('waterhole.login') ? new User() : null));

        $this->response = $gate->inspect('waterhole.post.comment', $post);
    }

    public function render()
    {
        return $this->view('waterhole::components.post-sidebar');
    }
}
