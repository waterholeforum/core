<?php

namespace Waterhole\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use s9e\TextFormatter\Utils;
use Waterhole\Layouts\ListLayout;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class PostListItem extends Component
{
    use Streamable;

    public array $config;

    public function __construct(public Post $post, public string|HtmlString|null $excerpt = null)
    {
        $this->config = $post->channel->layout_config[ListLayout::class] ?? [];

        if ($this->config['show_excerpt'] ?? false) {
            $this->excerpt ??= Str::limit(Utils::removeFormatting($post->parsed_body), 200);
        }
    }

    public function render()
    {
        return $this->view('waterhole::components.post-list-item');
    }
}
