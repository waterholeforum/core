<?php

namespace Waterhole\Feed;

use Closure;
use Illuminate\Http\Request;
use Waterhole\Models\Post;

class PostFeed extends Feed
{
    private ?string $defaultLayout;

    public function __construct(
        Request $request,
        array $filters,
        string $defaultLayout,
        Closure $scope = null,
    ) {
        $query = Post::query()
            ->with([
                'user',
                'channel.userState',
                'channel.postsReactionSet',
                'channel.commentsReactionSet',
                'lastComment.user',
                'userState',
                'reactions.reactionType',
                'reactions.user',
            ])
            ->withCount('unreadComments');

        if ($scope) {
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
