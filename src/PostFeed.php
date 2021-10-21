<?php

namespace Waterhole;

use Closure;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Extend\FeedSort;
use Waterhole\Models\Post;
use Waterhole\Sorts\Sort;

class PostFeed
{
    private Collection $sorts;
    private ?string $defaultSort;
    private ?string $defaultLayout;
    private ?Closure $scope;
    private Request $request;

    public function __construct(
        Request $request,
        Closure $scope = null,
        array $sorts = null,
        string $defaultSort = null,
        string $defaultLayout = null,
    ) {
        $this->request = $request;
        $this->scope = $scope;
        $this->defaultSort = $defaultSort;
        $this->defaultLayout = $defaultLayout;

        $this->setSorts($sorts);
    }

    public function posts(): CursorPaginator
    {
        $query = Post::with('user', 'channel.userState', 'lastComment.user', 'userState', 'likedBy');

        if ($this->scope) {
            ($this->scope)($query);
        }

        if ($sort = $this->currentSort()) {
            $sort->apply($query);
        }

        return $query->cursorPaginate();
    }

    public function setSorts(?array $handles)
    {
        $handles ??= config('waterhole.forum.sorts');

        $this->sorts = FeedSort::getInstances()
            ->filter(fn(Sort $sort) => in_array($sort->handle(), $handles))
            ->values();
    }

    public function sorts(): Collection
    {
        return $this->sorts;
    }

    public function defaultSort(): string
    {
        return $this->defaultSort ?: config('waterhole.forum.default_sort');
    }

    public function currentSort(): ?Sort
    {
        return $this->sorts->first(function (Sort $sort) {
            return $sort->handle() === $this->request->query(
                'sort',
                $this->defaultSort()
            );
        }, $this->sorts[0]);
    }

    public function defaultLayout(): string
    {
        return $this->defaultLayout ?: config('waterhole.forum.default_layout');
    }

    public function currentLayout(): string
    {
        $layout = $this->request->query('layout');

        if (in_array($layout, ['list', 'cards'])) {
            return $layout;
        }

        return $this->defaultLayout();
    }
}
