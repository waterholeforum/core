<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class NewComment extends Notification
{
    protected Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function content(): Comment
    {
        return $this->comment;
    }

    public function sender(): User
    {
        return $this->comment->user;
    }

    public function icon(): string
    {
        return 'heroicon-o-chat-alt';
    }

    public function title(): string
    {
        return "New comment in **{$this->comment->post->title}**";
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
        return 'View Comment';
    }

    public function reason(): string
    {
        return 'You received this because you are following this post.';
    }

    public function unsubscribeText(): string
    {
        return 'Unfollow this post';
    }

    public function unsubscribe(User $user): void
    {
        $this->comment->post->loadUserState($user)->unfollow();
    }

    public static function description(): string
    {
        return 'New comments on followed posts';
    }

    public static function load(Collection $notifications): void
    {
        $notifications->load('content.post', 'content.user');
    }
}
