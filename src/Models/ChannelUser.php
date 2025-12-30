<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $channel_id
 * @property int $user_id
 * @property null|string $notifications
 * @property null|\Carbon\Carbon $followed_at
 * @property-read Channel $channel
 * @property-read User $user
 */
class ChannelUser extends Model
{
    public $timestamps = false;

    protected $table = 'channel_user';

    protected $casts = [
        'followed_at' => 'datetime',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getKey(): string
    {
        return $this->channel_id . '-' . $this->user_id;
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query->where('channel_id', $this->channel_id)->where('user_id', $this->user_id);
    }
}
