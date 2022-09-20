<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;

class CommentFrame extends Component
{
    public function __construct(
        public Comment $comment,
        public bool $withReplies = false,
        public bool $lazy = false,
    ) {
    }

    public function render()
    {
        return <<<'blade'
            <turbo-frame id="@domid($comment)" @if ($lazy) src="{{ $comment->url }}" loading="lazy" @endif data-controller="load-backwards">
                @unless ($lazy)
                    <x-waterhole::comment-full :comment="$comment" :with-replies="$withReplies"/>
                @endunless
            </turbo-frame>
        blade;
    }
}
