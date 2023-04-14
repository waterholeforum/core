<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Layouts\CardsLayout;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class PostCard extends Component
{
    use Streamable;

    public array $config;

    public function __construct(public Post $post)
    {
        $this->config = $post->channel->layout_config[CardsLayout::class] ?? [];
    }

    public function render()
    {
        return $this->view('waterhole::components.post-card');
    }
}
