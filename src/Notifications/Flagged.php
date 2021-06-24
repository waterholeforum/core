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

use Waterhole\Models\Flag;
use Waterhole\Models\Post;
use Illuminate\Support\Facades\URL;

class Flagged extends Notification implements Mailable
{
    /**
     * @var Post
     */
    protected $flag;

    public function __construct(Flag $flag)
    {
        $this->flag = $flag;
    }

    public function sender($notifiable)
    {
        return $this->flag->user;
    }

    public function subject($notifiable)
    {
        return $this->flag->post;
    }

    public function content($notifiable)
    {
        return $this->flag;
    }

    /**
     * The text to use for the email subject and to display in the header
     * of the email. Markdown is allowed.
     */
    public function mailText($notifiable): string
    {
        return '**'.$this->flag->post->user->username.'** flagged a post';
    }

    /**
     * The text to use for the call-to-action button.
     */
    public function mailActionText($notifiable): ?string
    {
        return 'View Post';
    }

    /**
     * The URL that the call-to-action button links to.
     */
    public function mailActionUrl($notifiable): ?string
    {
        // return route('discussion', [
        //     'id' => $this->flag->post->discussion->id,
        //     'slug' => $this->flag->post->discussion->slug,
        //     'number' => $this->flag->post->number
        // ]);
    }

    /**
     * An excerpt from the content to display in the email.
     */
    public function mailExcerpt($notifiable): ?string
    {
        return $this->flag->post->render();
    }

    /**
     * A sentence explaining why the email notification was received.
     */
    public function mailReason($notifiable): ?string
    {
        return 'You received this because you subscribed to email notifications for flags.';
    }

    /**
     * The text to use for the unsubscribe link.
     */
    public function mailUnsubscribeText($notifiable): ?string
    {
        return 'Unsubscribe from flag notifications';
    }

    /**
     * The URL that the unsubscribe link links to.
     */
    public function mailUnsubscribeUrl($notifiable): ?string
    {
        return URL::signedRoute('unsubscribe.notifications', [
            'actor' => $notifiable->getKey(),
            'type' => static::class
        ]);
    }
}
