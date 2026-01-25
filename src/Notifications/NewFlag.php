<?php

namespace Waterhole\Notifications;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Flag;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use function Waterhole\emojify;

class NewFlag extends Notification
{
    public function __construct(protected Flag $flag) {}

    public function content(): Flag
    {
        return $this->flag;
    }

    public function sender(): ?User
    {
        return $this->flag->createdBy;
    }

    public function icon(): string
    {
        return 'tabler-flag';
    }

    public function title(): HtmlString
    {
        $post =
            $this->flag->subject instanceof Post
                ? $this->flag->subject
                : $this->flag->subject->post;

        $key =
            $this->flag->subject instanceof Comment
                ? 'waterhole::notifications.flagged-comment-title'
                : 'waterhole::notifications.flagged-post-title';

        return new HtmlString(
            __($key, ['post' => '<strong>' . emojify($post->title) . '</strong>']),
        );
    }

    public function excerpt(): string
    {
        $reason = Lang::has($key = "waterhole::forum.report-reason-{$this->flag->reason}-label")
            ? __($key)
            : Str::headline($this->flag->reason);

        $excerpt = Str::limit(strip_tags($this->flag->subject->body_html ?? ''), 200);

        return $excerpt ? $reason . ' - ' . $excerpt : $reason;
    }

    public function url(): ?string
    {
        return $this->flag->subject?->flagUrl();
    }

    public function button(): ?string
    {
        return $this->flag->subject instanceof Comment
            ? __('waterhole::notifications.view-comment-button')
            : __('waterhole::notifications.view-post-button');
    }

    public function group(): ?Model
    {
        return $this->flag->subject;
    }

    public function groupedUrl(): ?string
    {
        return $this->flag->subject?->flagUrl();
    }

    public function reason(): string
    {
        return __('waterhole::notifications.new-flag-reason');
    }

    public function unsubscribeText(): string
    {
        return __('waterhole::notifications.new-flag-unsubscribe');
    }

    public static function description(): string
    {
        return __('waterhole::notifications.new-flag-description');
    }

    public static function availableFor(User $user): bool
    {
        return Channel::allPermitted($user, 'moderate') !== [];
    }

    public static function load(Collection $notifications): void
    {
        $notifications->load(['content.createdBy', 'content.subject']);

        $notifications->loadMorph('content.subject', [
            Comment::class => ['post'],
        ]);
    }
}
