<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Http\Request;
use Waterhole\Models\Post;

class PostFeed extends Feed
{
    // TODO: move this stuff to extenders
    public static array $eagerLoad = [
        'user',
        'channel.userState',
        'channel.postsReactionSet',
        'channel.commentsReactionSet',
        'lastComment.user',
        'userState',
        'reactions.reactionType',
        'reactions.user',
    ];

    public static array $scopes = [];

    private ?string $defaultLayout;

    public function __construct(
        Request $request,
        array $filters,
        string $defaultLayout,
        Closure $scope = null,
    ) {
        $query = Post::query()
            ->with(static::$eagerLoad)
            ->withCount('unreadComments');

        if ($scope) {
            $scope($query);
        }

        foreach (static::$scopes as $scope) {
            $scope($query);
        }

        parent::__construct($request, $query, $filters);

        $this->defaultLayout = $defaultLayout;
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
