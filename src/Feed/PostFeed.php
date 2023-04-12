<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;
use Waterhole\Extend\PostFeedQuery;
use Waterhole\Models\Post;

class PostFeed extends Feed
{
    public static array $scopes = [];

    private ?string $defaultLayout;

    public function __construct(
        Request $request,
        array $filters,
        string $defaultLayout,
        Closure $scope = null,
    ) {
        $query = Post::query();

        if ($scope) {
            $scope($query);
        }

        foreach (static::$scopes as $scope) {
            $scope($query);
        }

        foreach (PostFeedQuery::values() as $scope) {
            $scope($query);
        }

        parent::__construct($request, $query, $filters);

        $this->defaultLayout = $defaultLayout;
    }

    public function items(): CursorPaginator
    {
        if ($this->currentLayout() === 'cards') {
            $this->query->with('mentions', 'attachments');
        }

        return parent::items();
    }

    public function defaultLayout(): string
    {
        return $this->defaultLayout;
    }

    public function currentLayout(): string
    {
        if (in_array($layout = $this->request->query('layout'), ['list', 'cards'])) {
            return $layout;
        }

        return $this->defaultLayout();
    }
}
