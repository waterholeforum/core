<?php

namespace Waterhole\Extend\Query;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Extend\Support\Set;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

/**
 * Comment query callbacks.
 *
 * Use this extender to eager load relations or constrain comment list results.
 */
class CommentQuery extends Set
{
    public Set $feed;
    public Set $thread;

    public function __construct()
    {
        $this->feed = new Set();
        $this->thread = new Set();

        $this->add(function (Builder $query) {
            $query->with([
                'user.groups',
                'parent.user.groups',
                'mentions.mentionable',
                'attachments',
                'reactionCounts',
            ]);
        });

        $this->feed->add(function (Builder $query) {
            $query->with([
                'post.userState',
                'post.channel',
                'post.channel.commentsReactionSet.reactionTypes',
                'parent.post',
            ]);

            if (Channel::allPermitted(auth()->user(), 'moderate') !== []) {
                $query->with(['pendingFlags.createdBy', 'deletedBy']);
            }
        });

        $this->thread->add(function (Builder $query, ?Post $post = null) {
            if ($post && $post->canModerate(auth()->user())) {
                $query->with(['pendingFlags.createdBy', 'deletedBy']);
            }
        });
    }
}
