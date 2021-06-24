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

use Waterhole\Category;
use Waterhole\Models\Discussion;
use Illuminate\Support\Facades\URL;

class NewDiscussion extends Notification implements Mailable
{
    /**
     * @var Discussion
     */
    protected $discussion;

    /**
     * @var Category
     */
    protected $category;

    public function __construct(Discussion $discussion, Category $category)
    {
        $this->discussion = $discussion;
        $this->category = $category;
    }

    public function sender($notifiable)
    {
        return $this->discussion->user;
    }

    public function subject($notifiable)
    {
        return $this->category;
    }

    public function content($notifiable)
    {
        return $this->discussion;
    }

    public function mailText($notifiable): string
    {
        return trans('notifications.new_discussion', [
            'user' => '**'.$this->discussion->user->username.'**',
            'category' => '**'.$this->category->name.'**'
        ]);
    }

    public function mailActionText($notifiable): ?string
    {
        return trans('notifications.new_discussion_view');
    }

    public function mailActionUrl($notifiable): ?string
    {
        // return route('discussion', [
        //     'id' => $this->discussion->id,
        //     'slug' => $this->discussion->slug,
        // ]);
    }

    public function mailExcerpt($notifiable): ?string
    {
        return '<h1>'.$this->discussion->title.'</h1>'.$this->discussion->firstComment->render;
    }

    public function mailReason($notifiable): ?string
    {
        return trans('notifications.new_discussion_reason');
    }

    public function mailUnsubscribeText($notifiable): ?string
    {
        return trans('notifications.new_discussion_unsubscribe');
    }

    public function mailUnsubscribeUrl($notifiable): ?string
    {
        return URL::signedRoute('unsubscribe.category', [
            'user' => $notifiable->getKey(),
            'category' => $this->category->getKey()
        ]);
    }
}
