<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\ManagesClasses;
use Illuminate\Support\Facades\Auth;
use Tobyz\JsonApiModels\Model;

class DiscussionClasses
{
    use ManagesClasses;

    protected static function defaultClasses(Model $discussion): array
    {
        return [
            'is-unread' => Auth::check() && ($discussion->bookmark->lastReadPostNumber ?? 0) < $discussion->lastCommentNumber,
            'is-read' => Auth::check() && ($discussion->bookmark->lastReadPostNumber ?? 0) >= $discussion->lastCommentNumber,
            'is-new' => Auth::check() && ! $discussion->bookmark,
            'is-trashed' => $discussion->deletedAt,
            'is-actor' => $discussion->user && $discussion->user->id == Auth::id(),
            'has-replies' => $discussion->commentCount > 1,
            'is-locked' => $discussion->isLocked,
            'is-subscribed' => ! ($discussion->subscription->isMuted ?? true),
            'is-muted' => $discussion->subscription->isMuted ?? false,
            'has-draft' => $discussion->bookmark->draftSavedAt ?? false,
        ];
    }
}
