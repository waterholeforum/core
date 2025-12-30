<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $post_id
 * @property int $user_id
 * @property null|\Carbon\Carbon $last_read_at
 * @property null|string $notifications
 * @property null|\Carbon\Carbon $followed_at
 * @property null|\Carbon\Carbon $mentioned_at
 * @property-read Post $post
 * @property-read User $user
 */
class PostUser extends Model
{
    public $timestamps = false;

    protected $table = 'post_user';

    protected $casts = [
        'last_read_at' => 'datetime',
        'followed_at' => 'datetime',
        'mentioned_at' => 'datetime',
    ];

    /**
     * Mark this post as having been read by the user.
     */
    public function read(): static
    {
        $this->last_read_at = now();

        return $this;
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getKey(): string
    {
        return $this->post_id . '-' . $this->user_id;
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query->where('post_id', $this->post_id)->where('user_id', $this->user_id);
    }
}
