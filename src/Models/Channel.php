<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Waterhole\Extend\PostFilters;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\HasVisibility;
use Waterhole\Models\Concerns\Structurable;

class Channel extends Model
{
    use HasUserState;
    use Followable;
    use Structurable;
    use HasPermissions;
    use HasIcon;
    use HasVisibility;

    public $timestamps = false;

    protected $casts = [
        'filters' => 'json',
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
            'filters' => ['required_with:custom_filters', 'array'],
            'filters.*' => ['string', 'distinct', Rule::in(PostFilters::values())],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
