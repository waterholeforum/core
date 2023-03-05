<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class NewComment extends Notification
{
    public function __construct(protected Comment $comment)
    {
    }

    public function content(): Comment
    {
        return $this->comment;
    }

    public function sender(): ?User
    {
        return $this->comment->user;
    }

    public function icon(): string
    {
        return 'tabler-message-circle-2';
    }

    public function title(): string
    {
        return __('waterhole::notifications.new-comment-title', [
            'post' => "**{$this->comment->post->title}**",
        ]);
    }

    public function excerpt(): HtmlString
    {
        return $this->comment->body_html;
    }

    public function url(): string
    {
        return $this->comment->post_url;
    }

    public function group(): Post
    {
        return $this->comment->post;
    }

    public function groupedUrl(): string
    {
        return $this->comment->post->unread_url;
    }

    public function button(): string
    {
        return __('waterhole::notifications.view-comment-button');
    }

    public function reason(): string
    {
        return __('waterhole::notifications.new-comment-reason');
    }

    public function unsubscribeText(): string
    {
        return __('waterhole::notifications.new-comment-unsubscribe');
    }

    public function unsubscribe(User $user): void
    {
        $this->comment->post->loadUserState($user)->unfollow();
    }

    public static function description(): string
    {
        return __('waterhole::notifications.new-comment-description');
    }

    public static function load(Collection $notifications): void
    {
        $notifications->load('content.post', 'content.user');
    }
}
