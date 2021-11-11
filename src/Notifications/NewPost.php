<?php

namespace Waterhole\Notifications;

use Waterhole\Models\Post;

class NewPost extends Notification// implements Mailable
{
    protected Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function sender($notifiable)
    {
        return $this->post->user;
    }

    public function subject($notifiable)
    {
        return $this->post->channel;
    }

    public function content($notifiable)
    {
        return $this->post;
    }

    // public function mailText($notifiable): string
    // {
    //     return trans('notifications.new_discussion', [
    //         'user' => '**'.$this->discussion->user->username.'**',
    //         'category' => '**'.$this->category->name.'**'
    //     ]);
    // }
    //
    // public function mailActionText($notifiable): ?string
    // {
    //     return trans('notifications.new_discussion_view');
    // }
    //
    // public function mailActionUrl($notifiable): ?string
    // {
    //     // return route('discussion', [
    //     //     'id' => $this->discussion->id,
    //     //     'slug' => $this->discussion->slug,
    //     // ]);
    // }
    //
    // public function mailExcerpt($notifiable): ?string
    // {
    //     return '<h1>'.$this->discussion->title.'</h1>'.$this->discussion->firstComment->render;
    // }
    //
    // public function mailReason($notifiable): ?string
    // {
    //     return trans('notifications.new_discussion_reason');
    // }
    //
    // public function mailUnsubscribeText($notifiable): ?string
    // {
    //     return trans('notifications.new_discussion_unsubscribe');
    // }
    //
    // public function mailUnsubscribeUrl($notifiable): ?string
    // {
    //     return URL::signedRoute('unsubscribe.category', [
    //         'user' => $notifiable->getKey(),
    //         'category' => $this->category->getKey()
    //     ]);
    // }
}
