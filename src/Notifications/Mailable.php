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

interface Mailable
{
    /**
     * The text to use for the email subject and to display in the header
     * of the email. Markdown is allowed.
     */
    public function mailText($notifiable): string;

    /**
     * The text to use for the call-to-action button.
     */
    public function mailActionText($notifiable): ?string;

    /**
     * The URL that the call-to-action button links to.
     */
    public function mailActionUrl($notifiable): ?string;

    /**
     * An excerpt from the content to display in the email.
     */
    public function mailExcerpt($notifiable): ?string;

    /**
     * A sentence explaining why the email notification was received.
     */
    public function mailReason($notifiable): ?string;

    /**
     * The text to use for the unsubscribe link.
     */
    public function mailUnsubscribeText($notifiable): ?string;

    /**
     * The URL that the unsubscribe link links to.
     */
    public function mailUnsubscribeUrl($notifiable): ?string;
}
