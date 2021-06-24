<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Notifications;

use Waterhole\Posts\CommentPost;
use Illuminate\Support\Facades\URL;

class Mentioned extends Notification implements Mailable
{
    /**
     * @var CommentPost
     */
    protected $post;

    public function __construct(CommentPost $post)
    {
        $this->post = $post;
    }

    public function sender($notifiable)
    {
        return $this->post->user;
    }

    public function subject($notifiable)
    {
        return $this->post->discussion;
    }

    public function content($notifiable)
    {
        return $this->post;
    }

    public function mailText($notifiable): string
    {
        return trans('notifications.mentioned', [
            'user' => '**'.$this->post->user->username.'**',
            'discussion' => '['.$this->post->discussion->title.']('.$this->mailActionUrl($notifiable).')'
        ]);
    }

    public function mailActionText($notifiable): ?string
    {
        return trans('notifications.mentioned_view_post');
    }

    public function mailActionUrl($notifiable): ?string
    {
        return route('post', ['post' => $this->post]);
        // return route('discussion', [
        //     'id' => $this->post->discussion->id,
        //     'slug' => $this->post->discussion->slug,
        //     'number' => $this->post->number
        // ]);
    }

    public function mailExcerpt($notifiable): ?string
    {
        return $this->post->render();
    }

    public function mailReason($notifiable): ?string
    {
        return trans('notifications.mentioned_reason');
    }

    public function mailUnsubscribeText($notifiable): ?string
    {
        return trans('notifications.mentioned_unsubscribe');
    }

    public function mailUnsubscribeUrl($notifiable): ?string
    {
        return URL::signedRoute('unsubscribe.notifications', [
            'actor' => $notifiable->getKey(),
            'type' => static::class
        ]);
    }
}
