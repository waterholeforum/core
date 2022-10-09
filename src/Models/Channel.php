<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\UsesFormatter;
use Waterhole\Models\Concerns\ValidatesData;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $description
 * @property ?string $instructions
 * @property ?array $filters
 * @property ?string $default_layout
 * @property bool $sandbox
 * @property-read \Illuminate\Database\Eloquent\Collection $posts
 * @property-read \Illuminate\Database\Eloquent\Collection $newPosts
 * @property-read \Illuminate\Database\Eloquent\Collection $unreadPosts
 * @property-read ?ChannelUser $userState
 * @property-read string $url
 * @property-read string $edit_url
 */
class Channel extends Model
{
    use Followable;
    use HasIcon;
    use HasPermissions;
    use HasUserState;
    use Structurable;
    use ValidatesData;
    use UsesFormatter;

    public $timestamps = false;

    protected $casts = [
        'filters' => 'json',
        'sandbox' => 'bool',
    ];

    /**
     * Relationship with the channel's posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship with posts that are new since this channel was followed.
     */
    public function newPosts(): HasMany
    {
        return $this->posts()
            ->whereDoesntHave('userState')
            ->whereHas('channel.userState', function ($query) {
                $query->whereColumn('posts.created_at', '>', 'followed_at');
            });
    }

    /**
     * Relationship with posts that are followed and contain unread content.
     */
    public function unreadPosts(): HasMany
    {
        return $this->posts()
            ->following()
            ->unread();
    }

    public function abilities(): array
    {
        return ['view', 'comment', 'post', 'moderate'];
    }

    public function defaultAbilities(): array
    {
        return ['view', 'comment', 'post'];
    }

    public function getUrlAttribute(): string
    {
        return route('waterhole.channels.show', ['channel' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.channels.edit', ['channel' => $this]);
    }
}
