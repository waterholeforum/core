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
 * @property null|string $draft_body
 * @property null|int $draft_parent_id
 * @property null|\Carbon\Carbon $draft_saved_at
 * @property-read Post $post
 * @property-read User $user
 */
class PostUser extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'post_user';

    protected $casts = [
        'last_read_at' => 'datetime',
        'followed_at' => 'datetime',
        'mentioned_at' => 'datetime',
        'draft_saved_at' => 'datetime',
    ];

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

    /**
     * Mark this post as having been read by the user.
     */
    public function read(): static
    {
        $this->last_read_at = now();

        return $this;
    }

    public function hasDraft(): bool
    {
        return filled($this->draft_body);
    }

    public function discardDraft(): static
    {
        $this->draft_body = null;
        $this->draft_parent_id = null;
        $this->draft_saved_at = null;

        return $this;
    }

    public function setDraft(?string $body, ?string $parentId = null): static
    {
        $this->draft_body = filled($body) ? $body : null;
        $this->draft_parent_id = filled($body) ? $parentId : null;
        $this->draft_saved_at = filled($body) ? now() : null;

        return $this;
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query->where('post_id', $this->post_id)->where('user_id', $this->user_id);
    }
}
