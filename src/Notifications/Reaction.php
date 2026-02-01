<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\Reaction as ReactionModel;
use Waterhole\Models\User;
use function Waterhole\emojify;

class Reaction extends Notification
{
    public function __construct(protected ReactionModel $reaction) {}

    public function content(): ReactionModel
    {
        return $this->reaction;
    }

    public function sender(): ?User
    {
        return $this->reaction->user;
    }

    public function icon(): ?string
    {
        return $this->reaction->reactionType?->icon ?? 'tabler-mood-smile';
    }

    public function title(): HtmlString
    {
        $content = $this->reaction->content;
        $post = $content instanceof Post ? $content : $content->post;

        return new HtmlString(
            __(
                $content instanceof Post
                    ? 'waterhole::notifications.reaction-post-title'
                    : 'waterhole::notifications.reaction-comment-title',
                [
                    'count' => $content->reactions_count,
                    'post' => '<strong>' . emojify($post->title) . '</strong>',
                ],
            ),
        );
    }

    public function excerpt(): null|string|HtmlString
    {
        return $this->reaction->content?->body_html;
    }

    public function url(): ?string
    {
        return $this->reaction->content instanceof Comment
            ? $this->reaction->post_url
            : $this->reaction->url;
    }

    public function group(): ?Model
    {
        return $this->reaction->content;
    }

    public static function description(): string
    {
        return __('waterhole::notifications.reaction-description');
    }

    public static function channels(): array
    {
        return ['database'];
    }

    public static function load(Collection $notifications): void
    {
        $notifications
            ->load('content.user', 'content.reactionType', 'content.content')
            ->loadMorph('content.content', [Comment::class => ['post']])
            ->loadMorphCount('content.content', [
                Post::class => ['reactions'],
                Comment::class => ['reactions'],
            ]);
    }
}
