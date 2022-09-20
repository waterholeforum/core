<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole;
use Waterhole\Models\Post;
use Waterhole\Views\Components\Concerns\Streamable;

class PostCardsItem extends Component
{
    use Streamable;

    public Post $post;
    public string $excerpt;
    public bool $truncated;

    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->excerpt = (string) Waterhole\emojify(Waterhole\truncate_html($post->body_html, 500));
        $this->truncated = str_ends_with(strip_tags($this->excerpt), '...');
    }

    public function render()
    {
        return view('waterhole::components.post-cards-item');
    }
}
