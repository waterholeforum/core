<?php

namespace Waterhole;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Extend\FeedSort;
use Waterhole\Models\Post;
use Waterhole\Sorts\Sort;

class PostFeed
{
    private Collection $sorts;
    private ?string $defaultSort;
    private ?Closure $scope;
    private Request $request;

    public function __construct(Request $request, Closure $scope = null, array $sorts = null, string $defaultSort = null)
    {
        $this->request = $request;
        $this->scope = $scope;
        $this->defaultSort = $defaultSort;

        $this->setSorts($sorts);
    }

    public function posts()
    {
        $query = Post::query()->with('userState');

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

    public function setDefaultSort(string $handle)
    {
        $this->defaultSort = $handle;
    }

    public function defaultSort(): string
    {
        return $this->defaultSort ?: config('waterhole.forum.default_sort');
    }

    public function currentSort(): ?Sort
    {
        return $this->sorts->first(function (Sort $sort) {
            return $sort->handle() === $this->request->query('sort', $this->defaultSort());
        }, $this->sorts[0]);
    }
}
