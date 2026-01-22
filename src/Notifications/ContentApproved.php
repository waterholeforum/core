<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use function Waterhole\emojify;

class ContentApproved extends Notification
{
    public function __construct(protected Post|Comment $subject)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function content(): Model
    {
        return $this->subject;
    }

    public function icon(): string
    {
        return $this->subject->channel->icon;
    }

    public function title(): HtmlString
    {
        if ($this->subject instanceof Post) {
            return new HtmlString(
                __('waterhole::notifications.post-approved-title', [
                    'post' => '<strong>' . emojify($this->subject->title) . '</strong>',
                ]),
            );
        }

        return new HtmlString(
            __('waterhole::notifications.comment-approved-title', [
                'post' => '<strong>' . emojify($this->subject->post->title) . '</strong>',
            ]),
        );
    }

    public function excerpt(): HtmlString
    {
        return $this->subject->body_html;
    }

    public function url(): string
    {
        return $this->subject instanceof Post ? $this->subject->url : $this->subject->post_url;
    }

    public function button(): string
    {
        return $this->subject instanceof Post
            ? __('waterhole::notifications.view-post-button')
            : __('waterhole::notifications.view-comment-button');
    }

    public static function load(Collection $notifications): void
    {
        $notifications->loadMorph('content', [
            Post::class => ['user', 'channel'],
            Comment::class => ['user', 'channel', 'post'],
        ]);
    }
}
