<?php

namespace Waterhole\View\Components;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class IndexCreatePost extends Component
{
    public bool|Response $response;

    public function __construct(public ?Channel $channel = null)
    {
        $gate = Gate::forUser(Auth::user() ?: new User());

        $this->response = $channel
            ? $gate->inspect('waterhole.channel.post', $channel)
            : $gate->inspect('waterhole.post.create');
    }

    public function render()
    {
        return $this->view('waterhole::components.index-create-post');
    }
}
