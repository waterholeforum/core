<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Waterhole\Models\Comment;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use function Waterhole\emojify;

class ContentRemoved extends Notification
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
        return 'tabler-trash';
    }

    public function title(): HtmlString
    {
        if ($this->subject instanceof Post) {
            return new HtmlString(
                __('waterhole::notifications.post-removed-title', [
                    'post' => '<strong>' . emojify($this->subject->title) . '</strong>',
                ]),
            );
        }

        return new HtmlString(
            __('waterhole::notifications.comment-removed-title', [
                'post' => '<strong>' . emojify($this->subject->post->title) . '</strong>',
            ]),
        );
    }

    public function excerpt(): ?string
    {
        $message = $this->subject->deleted_message;

        if (!$message && $this->subject->deleted_reason) {
            $message = Lang::has(
                $key = "waterhole::forum.report-reason-{$this->subject->deleted_reason}-label",
            )
                ? __($key)
                : Str::headline($this->subject->deleted_reason);
        }

        return $message;
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
            Comment::class => ['post'],
        ]);
    }
}
