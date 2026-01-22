<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\User;
use Waterhole\View\Components\ModerationBadge;
use Waterhole\View\TurboStream;
use Waterhole\Waterhole;

class FlagReceived implements ShouldBroadcast, TransactionalEvent
{
    public function __construct(protected User $user)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel($this->user->broadcastChannel());
    }

    public function broadcastWith(): array
    {
        return [
            'streams' => TurboStream::replace(new ModerationBadge($this->user)),
        ];
    }

    public static function dispatchForChannel(Channel $channel): void
    {
        static::moderatorsForChannel($channel)->each(fn(User $user) => event(new static($user)));
    }

    protected static function moderatorsForChannel(Channel $channel): Collection
    {
        $permissions = Waterhole::permissions()
            ->scope($channel)
            ->where('ability', 'moderate');

        $moderatorGroupIds = $permissions
            ->where('recipient_type', (new Group())->getMorphClass())
            ->pluck('recipient_id');

        $moderatorUserIds = $permissions
            ->where('recipient_type', (new User())->getMorphClass())
            ->pluck('recipient_id');

        $groupUserIds = DB::table('group_user')
            ->whereIn('group_id', [...$moderatorGroupIds, Group::ADMIN_ID])
            ->pluck('user_id');

        $userIds = $groupUserIds
            ->merge($moderatorUserIds)
            ->unique()
            ->values();

        return User::with('groups')->findMany($userIds);
    }
}
