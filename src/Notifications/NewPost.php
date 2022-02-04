<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class NewPost extends Notification
{
    protected Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function content(): Post
    {
        return $this->post;
    }

    public function sender(): User
    {
        return $this->post->user;
    }

    public function icon(): string
    {
        return $this->post->channel->icon;
    }

    public function title(): string
    {
        return "New post in {$this->post->channel->name}: **{$this->post->title}**";
    }

    public function excerpt(): string
    {
        return $this->post->body_html;
    }

    public function url(): string
    {
        return $this->post->url;
    }

    public function button(): string
    {
        return 'View Post';
    }

    public function reason(): string
    {
        return 'You received this because you are following this channel.';
    }

    public function unsubscribeText(): string
    {
        return 'Unfollow this channel';
    }

    public function unsubscribe(User $user): void
    {
        $this->post->channel->loadUserState($user)->unfollow();
    }

    public static function description(): string
    {
        return 'New posts in followed channels';
    }

    public static function load(Collection $notifications): void
    {
        $notifications->load('content.user', 'content.channel');
    }
}
