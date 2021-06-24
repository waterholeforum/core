<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Category;
use Waterhole\Models\Discussion;
use Waterhole\Models\User;

class Unsubscribe extends Controller
{
    /**
     * Unsubscribe the user from email notifications for a notification type.
     */
    public function type(int $user, string $type)
    {
        $user = User::findOrFail($user);

        if (isset($user->notification_channels[$type])) {
            $channels = $user->notification_channels;
            $channels[$type] = array_filter(
                $channels[$type],
                function ($channel) {
                    return $channel !== 'mail';
                }
            );

            $user->notification_channels = $channels;
            $user->save();
        }

        return redirect()
            ->route('discussionList')
            ->with('toast', trans('notifications.unsubscribed'));
    }

    /**
     * Unsubscribe the user from a discussion.
     */
    public function discussion(int $user, int $discussion)
    {
        $user = User::findOrFail($user);

        $discussion = Discussion::visibleTo($user)->findOrFail($discussion);

        $discussion->subscription($user)->delete();

        return redirect()
            ->route('discussionList')
            ->with('toast', trans('notifications.unsubscribed_discussion', ['discussion' => $discussion->title]));
    }

    /**
     * Unsubscribe the user from a category.
     */
    public function category(int $user, int $category)
    {
        $user = User::findOrFail($user);

        $category = Category::visibleTo($user)->findOrFail($category);

        $category->subscription($user)->delete();

        return redirect()
            ->route('discussionList')
            ->with('toast', trans('notifications.unsubscribed_category', ['category' => $category->name]));
    }
}
