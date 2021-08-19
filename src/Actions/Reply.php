<?php

namespace Waterhole\Actions;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class Reply extends Link
{
    public bool $hidden = false;
    public bool $bulk = false;

    public function name(): string
    {
        return 'Reply';
    }

    public function label($items): string|HtmlString
    {
        return $items[0]->reply_count ? $items[0]->reply_count.' replies' : 'Reply';
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
        if ($item->reply_count) {
            return $item->url.'?sort='.request('sort');
        }

        $params = $item instanceof Post
            ? ['post' => $item]
            : ['post' => $item->post, 'parent' => $item->id];

        return route('waterhole.posts.comments.create', $params);
    }
}
