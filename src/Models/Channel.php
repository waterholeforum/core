<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Waterhole\Database\Factories\ChannelFactory;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\UsesFormatter;
use Waterhole\View\Components\PostFeedChannel;
use Waterhole\View\TurboStream;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ?string $description
 * @property ?string $instructions
 * @property ?array $filters
 * @property ?string $layout
 * @property ?array $layout_config
 * @property bool $ignore
 * @property bool $answerable
 * @property bool $require_approval_posts
 * @property bool $require_approval_comments
 * @property ?array $translations
 * @property bool $posts_reactions_enabled
 * @property ?int $posts_reaction_set_id
 * @property bool $comments_reactions_enabled
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
    use HasFactory;
    use Followable;
    use HasIcon;
    use HasPermissions;
    use HasUserState;
    use Structurable;
    use UsesFormatter;

    public $timestamps = false;

    protected $casts = [
        'filters' => 'json',
        'layout_config' => 'json',
        'ignore' => 'bool',
        'answerable' => 'bool',
        'translations' => 'json',
        'require_approval_posts' => 'bool',
        'require_approval_comments' => 'bool',
        'posts_reactions_enabled' => 'bool',
        'comments_reactions_enabled' => 'bool',
    ];

    protected static function booting(): void
    {
        static::creating(function (self $model) {
            $model->slug ??= Str::slug($model->name);
        });
    }

    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }

    /**
     * Relationship with the channel's posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship with posts that are followed and contain unread content.
     */
    public function unreadPosts(): HasMany
    {
        return $this->posts()->following()->unread();
    }

    /**
     * Scope to select count of posts that are new since a channel was followed.
     */
    public function scopeWithNewPostsCount(Builder $query): void
    {
        $sub = Post::query()
            ->selectRaw('COUNT(*)')
            ->whereColumn('posts.channel_id', 'channels.id')
            ->whereDoesntHave('userState')
            ->where('created_at', '>', 'followed_at');

        $query
            ->leftJoinRelation('userState')
            ->selectRaw(
                'CASE WHEN followed_at IS NOT NULL THEN (' .
                    $sub->toSql() .
                    ') ELSE 0 END AS new_posts_count',
                $sub->getBindings(),
            );
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

    public function postsReactionSet(): BelongsTo
    {
        return $this->belongsTo(ReactionSet::class, 'posts_reaction_set_id')->withDefault(
            fn($model, $parent) => $parent->posts_reactions_enabled
                ? ReactionSet::defaultPosts()
                : null,
        );
    }

    public function commentsReactionSet(): BelongsTo
    {
        return $this->belongsTo(ReactionSet::class, 'comments_reaction_set_id')->withDefault(
            fn($model, $parent) => $parent->comments_reactions_enabled
                ? ReactionSet::defaultComments()
                : null,
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

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.channels.show', ['channel' => $this]),
        )->shouldCache();
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.structure.channels.edit', ['channel' => $this]),
        )->shouldCache();
    }

    public function scopeIgnoring(Builder $query): void
    {
        $query
            ->leftJoinRelation('userState')
            ->where('channel_user.notifications', 'ignore')
            ->orWhere(
                fn($query) => $query
                    ->where('ignore', true)
                    ->whereNull('channel_user.notifications'),
            );
    }

    public function isIgnored(): bool
    {
        return $this->userState?->notifications === 'ignore' ||
            (!$this->userState?->notifications && $this->ignore);
    }

    /**
     * Get the Turbo Streams that should be sent when this post is updated.
     */
    public function streamUpdated(): array
    {
        return [TurboStream::replace(new PostFeedChannel($this))];
    }
}
