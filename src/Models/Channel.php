<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\UsesFormatter;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $description
 * @property ?string $instructions
 * @property ?array $filters
 * @property ?string $default_layout
 * @property bool $sandbox
 * @property bool $answerable
 * @property ?int $posts_reaction_set_id
 * @property ?int $comments_reaction_set_id
 * @property-read \Illuminate\Database\Eloquent\Collection $posts
 * @property-read \Illuminate\Database\Eloquent\Collection $newPosts
 * @property-read \Illuminate\Database\Eloquent\Collection $unreadPosts
 * @property-read ?ChannelUser $userState
 * @property-read ReactionSet $postsReactionSet
 * @property-read ReactionSet $commentsReactionSet
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
    use UsesFormatter;

    public $timestamps = false;

    protected $casts = [
        'filters' => 'json',
        'sandbox' => 'bool',
        'answerable' => 'bool',
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

    public function postsReactionSet(): BelongsTo
    {
        return $this->belongsTo(ReactionSet::class, 'posts_reaction_set_id')->withDefault(
            fn() => ReactionSet::defaultPosts(),
        );
    }

    public function commentsReactionSet(): BelongsTo
    {
        return $this->belongsTo(ReactionSet::class, 'comments_reaction_set_id')->withDefault(
            fn() => ReactionSet::defaultComments(),
        );
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class);
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
        return route('waterhole.cp.structure.channels.edit', ['channel' => $this]);
    }

    public function scopeIgnoring(Builder $query): void
    {
        $query
            ->whereHas('userState', fn($query) => $query->where('notifications', 'ignore'))
            ->orWhere(
                fn($query) => $query
                    ->where('sandbox', true)
                    ->whereDoesntHave(
                        'userState',
                        fn($query) => $query->whereNotNull('notifications'),
                    ),
            );
    }

    public function isIgnored(): bool
    {
        return $this->userState?->notifications === 'ignore' ||
            (!$this->userState?->notifications && $this->sandbox);
    }
}
