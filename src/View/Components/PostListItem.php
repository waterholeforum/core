<?php

namespace Waterhole\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Waterhole\Layouts\ListLayout;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

use function Waterhole\emojify;

class PostListItem extends Component
{
    use Streamable;

    public array $config;
    public HtmlString|string $title;

    public function __construct(public Post $post, public string|HtmlString|null $excerpt = null)
    {
        $this->config = $post->channel->layout_config[ListLayout::class] ?? [];

        if ($this->config['show_excerpt'] ?? false) {
            $this->excerpt ??= emojify(Str::limit($post->body_text, 200));
        }

        $this->title = $post->title;

        if (!$this->title instanceof HtmlString) {
            $this->title = emojify($this->title);
        }
    }

    public function render()
    {
        return $this->view('waterhole::components.post-list-item');
    }
}
