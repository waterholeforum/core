<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Waterhole\Extend\FeedSort;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\Structurable;

class Channel extends Model
{
    use HasUserState;
    use Followable;
    use Structurable;
    use HasPermissions;
    use HasIcon;

    public $timestamps = false;

    protected $casts = [
        'sorts' => 'json',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * A relationship with posts that are new since this channel was followed.
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
     * A relationship with posts that are followed and unread.
     */
    public function unreadPosts(): HasMany
    {
        return $this->posts()->following()->unread();
    }

    public function getUrlAttribute(): string
    {
        return route('waterhole.channels.show', ['channel' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.channels.edit', ['channel' => $this]);
    }

    public function abilities(): array
    {
        return ['view', 'comment', 'post', 'moderate'];
    }

    public static function rules(Channel $channel = null): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('channels')->ignore($channel)],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'sandbox' => ['nullable', 'boolean'],
            'default_layout' => ['in:list,cards'],
            'sorts' => ['required_with:custom_sorts', 'array'],
            'sorts.*' => ['string', 'distinct', Rule::in(FeedSort::getInstances()->map->handle())],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
