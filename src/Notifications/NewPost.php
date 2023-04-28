<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class NewPost extends Notification
{
    public function __construct(protected Post $post)
    {
    }

    public function content(): Post
    {
        return $this->post;
    }

    public function sender(): ?User
    {
        return $this->post->user;
    }

    public function icon(): string
    {
        return $this->post->channel->icon;
    }

    public function title(): HtmlString
    {
        return new HtmlString(
            __('waterhole::notifications.new-post-title', [
                'channel' => e($this->post->channel->name),
                'post' => '<strong>' . e($this->post->title) . '</strong>',
            ]),
        );
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
        return __('waterhole::notifications.view-post-button');
    }

    public function reason(): string
    {
        return __('waterhole::notifications.new-post-reason');
    }

    public function unsubscribeText(): string
    {
        return __('waterhole::notifications.new-post-unsubscribe');
    }

    public function unsubscribe(User $user): void
    {
        $this->post->channel->loadUserState($user)->unfollow();
    }

    public static function description(): string
    {
        return __('waterhole::notifications.new-post-description');
    }

    public static function load(Collection $notifications): void
    {
        $notifications->load('content.user', 'content.channel');
    }
}
