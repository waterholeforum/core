<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\View\Components\Concerns\Streamable;

class CommentFrame extends Component
{
    use Streamable;

    public function __construct(
        public Comment $comment,
        public bool $withReplies = false,
        public bool $lazy = false,
    ) {
    }

    public function render()
    {
        return <<<'blade'
            <turbo-frame id="@domid($comment)" @if ($lazy) src="{{ $comment->url }}" @endif data-controller="load-backwards" {{ $attributes }}>
                @unless ($lazy)
                    <x-waterhole::comment-full :comment="$comment" :with-replies="$withReplies"/>
                @endunless
            </turbo-frame>
        blade;
    }
}
