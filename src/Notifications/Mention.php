<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;

class Mention extends Notification
{
    protected Post|Comment $content;
    protected Post $post;

    public function __construct(Post|Comment $content)
    {
        $this->content = $content;
        $this->post = $this->content instanceof Post ? $this->content : $this->content->post;
    }

    public static function load(Collection $notifications): void
    {
        $notifications->loadMorph('content', [
            Post::class => ['user'],
            Comment::class => ['post', 'user'],
        ]);
    }

    public function shouldSend($notifiable): bool
    {
        return ! $this->post->ignoredBy->contains($notifiable)
            && ! $this->post->channel->ignoredBy->contains($notifiable);
    }

    public function sender()
    {
        return $this->content->user;
    }

    public function content()
    {
        return $this->content;
    }

    public function icon()
    {
        return 'heroicon-o-at-symbol';
    }

    public function title(): string
    {
        return "Mentioned in **{$this->post->title}**";
    }

    public function excerpt(): HtmlString
    {
        return $this->content->body_html;
    }

    public function url(): string
    {
        return $this->content instanceof Post ? $this->content->url : $this->content->post_url;
    }

    public function button(): string
    {
        return 'View '.($this->content instanceof Post ? 'Post' : 'Comment');
    }

    public function reason(): string
    {
        return 'You received this because you are subscribed to mention notifications.';
    }

    public function unsubscribeText(): string
    {
        return 'Unsubscribe from mention notifications';
    }
}
