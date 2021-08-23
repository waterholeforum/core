<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Reply extends Link
{
    public bool $hidden = true;

    public function name(): string
    {
        return 'Reply';
    }

    public function label(Collection $items): string|HtmlString
    {
        return $items[0]->reply_count
            ? __('waterhole::forum.comment-reply-count', ['count' => $items[0]->reply_count])
            : 'Reply';
    }

    public function icon(Collection $items): string
    {
        return 'heroicon-o-chat';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post || $item instanceof Comment;
    }

    public function authorize(User $user, $item): bool
    {
        return $user->can('reply', $item);
    }

    public function link($item)
    {
        return $item->url.'#reply';
    }
}
