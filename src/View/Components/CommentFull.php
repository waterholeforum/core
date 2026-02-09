<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\View\Components\Concerns\Streamable;

class CommentFull extends Component
{
    use Streamable;

    public function __construct(
        public Comment $comment,
        public bool $withReplies = false,
        public bool|int $truncate = false,
        public bool $withStructuredData = false,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.comment-full');
    }
}
