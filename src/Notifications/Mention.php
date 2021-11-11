<?php

namespace Waterhole\Notifications;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;

class Mention extends Notification
{
    protected Post|Comment $model;

    public function __construct(Post|Comment $model)
    {
        $this->model = $model;
    }

    public function sender($notifiable)
    {
        return $this->model->user;
    }

    public function subject($notifiable)
    {
        return $this->model;
    }

    public function title(): string
    {
        $post = $this->model instanceof Post ? $this->model : $this->model->post;

        return "{$this->model->user->name} mentioned you in **{$post->title}**";
    }

    public function excerpt(): HtmlString
    {
        return $this->model->body_html;
    }

    public function button(): string
    {
        return 'View '.($this->model instanceof Post ? 'Post' : 'Comment');
    }

    public function url(): string
    {
        return $this->model->url;
    }

    public function reason(): string
    {
        return 'You received this because you subscribed to notifications for mentions.';
    }

    public function unsubscribeText(): string
    {
        return 'Unsubscribe from mention notifications';
    }

    public function unsubscribe(): void
    {
        // do something
    }
}
