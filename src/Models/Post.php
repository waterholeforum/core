<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Waterhole\Database\Factories\PostFactory;
use Waterhole\Events\NewPost;
use Waterhole\Extend\Query\PostScopes;
use Waterhole\Models\Concerns\Approvable;
use Waterhole\Models\Concerns\Deletable;
use Waterhole\Models\Concerns\Flaggable;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\NotificationContent;
use Waterhole\Models\Concerns\Reactable;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewPost as NewPostNotification;
use Waterhole\View\Components;
use Waterhole\View\TurboStream;

/**
 * @property int $id
 * @property int $channel_id
 * @property null|int $user_id
 * @property null|string $title
 * @property null|string $slug
 * @property null|\Carbon\Carbon $created_at
 * @property null|\Carbon\Carbon $edited_at
 * @property null|\Carbon\Carbon $last_activity_at
 * @property int $comment_count
 * @property int $score
 * @property bool $is_locked
 * @property null|int $answer_id
 * @property bool $is_pinned
 * @property-read Channel $channel
 * @property-read null|User $user
 * @property-read null|User $deletedBy
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 * @property-read \Illuminate\Database\Eloquent\Collection $unreadComments
 * @property-read \Illuminate\Database\Eloquent\Collection $tags
 * @property-read null|Comment $lastComment
 * @property-read null|PostUser $userState
 * @property-read null|Comment $answer
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $unread_url
 * @property-read null|int $unread_comments_count
 */
class Post extends Model
{
    use HasFactory;
    use Followable;
    use HasBody;
    use Reactable;
    use HasUserState;
    use NotificationContent;
    use Deletable;
    use Approvable;
    use Flaggable;

    public const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_locked' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public static function booting(): void
    {
        static::addGlobalScope('visible', function ($query) {
            $query->visible(Auth::user());
        });

        static::creating(function (Post $post) {
            $post->last_activity_at ??= now();
        });

        static::created(function (self $post) {
            $post->deliverCreatedEvents();
        });

        // Delete comments one at a time to trigger event listeners.
        static::forceDeleting(function (self $post) {
            $post->comments()->lazy()->each->delete();
        });

        static::saving(function (self $post) {
            $sign = $post->score <=> 0;
            $seconds = ($post->created_at ?: now())->unix() - 1134028003;
            $post->hotness = round(
                $sign * log10(max(abs($post->score ?: 0), 1)) + $seconds / 45000,
                10,
            );
        });
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    protected function deliverCreatedEvents(): void
    {
        if (!$this->is_approved) {
            return;
        }

        broadcast(new NewPost($this))->toOthers();

        // When a new post is created, send notifications to mentioned users.
        $users = $this->mentions
            ->except($this->user_id)
            ->filter(fn(User $user) => Post::visible($user)->whereKey($this->id)->exists());

        $this->usersWereMentioned($users);

        Notification::send($users, new Mention($this));

        // Send out a "new post" notification to all followers of this post's
        // channel, except for the user who created the post.
        Notification::send(
            $this->channel->followedBy->except($this->user_id),
            new NewPostNotification($this),
        );
    }

    /**
     * Update the user state for any users mentioned in this post.
     */
    public function usersWereMentioned(Collection $users): void
    {
        $postUserRows = $users
            ->map(
                fn(User $user) => [
                    'post_id' => $this->getKey(),
                    'user_id' => $user->getKey(),
                    'mentioned_at' => now(),
                ],
            )
            ->all();

        PostUser::upsert($postUserRows, ['post_id', 'user_id'], ['mentioned_at']);
    }

    /**
     * Query posts that are unread for the current user.
     */
    public function scopeUnread(Builder $query)
    {
        $query->whereDoesntHave('userState', function ($query) {
            $query->whereColumn('last_read_at', '>=', 'last_activity_at');
        });
    }

    /**
     * Scope to select count of comments that are unread.
     */
    public function scopeWithUnreadCommentsCount(Builder $query): void
    {
        $query
            ->leftJoinRelation('userState')
            ->selectSub(
                Comment::query()
                    ->withoutGlobalScope('visible')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('comments.post_id', 'posts.id')
                    ->whereColumn('comments.created_at', '>', 'last_read_at'),
                'unread_comments_count',
            );
    }

    /**
     * Relationship with the post's channel.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class)->withoutGlobalScope('visible');
    }

    /**
     * Relationship with the post's author.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the post's comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with the post's most recent comment.
     */
    public function lastComment(): HasOne
    {
        return $this->hasOne(Comment::class)->latestOfMany();
    }

    /**
     * Relationship with the post's answer comment.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'answer_id');
    }

    /**
     * Relationship with the post's tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeVisible(Builder $query, ?User $user): void
    {
        $query->withoutGlobalScope('visible');

        $moderationScope = fn(Builder $query, array $channelIds) => $query->orWhereIn(
            'channel_id',
            $channelIds,
        );

        $this->applyApprovalVisibility($query, $user, $moderationScope);

        $this->applyDeletionVisibility($query, $user, $moderationScope);

        foreach (resolve(PostScopes::class)->values() as $scope) {
            $query->where(fn($inner) => $scope($inner, $user));
        }
    }

    /**
     * Generate a URL for a particular comment index in this post.
     */
    public function urlAtIndex(int $index = 0): string
    {
        $params = ['post' => $this];

        if (($page = floor($index / (new Comment())->getPerPage()) + 1) > 1) {
            $params['page'] = $page;
        }

        return route('waterhole.posts.show', $params);
    }

    /**
     * Mark this post as having been edited just now.
     */
    public function markAsEdited(): static
    {
        $this->edited_at = now();

        return $this;
    }

    /**
     * Refresh the metadata about this post's comments.
     */
    public function refreshCommentMetadata(): static
    {
        $publicComments = $this->comments()->visible(null);

        $this->last_activity_at =
            (clone $publicComments)->latest()->value('created_at') ?: $this->created_at;

        $this->comment_count = (clone $publicComments)->count();

        return $this;
    }

    /**
     * Determine whether this post contains any new activity for the current user.
     */
    public function isUnread(): bool
    {
        return $this->userState && $this->last_activity_at > $this->userState->last_read_at;
    }

    /**
     * Determine whether this post has been fully read by the current user.
     */
    public function isRead(): bool
    {
        return $this->userState && !$this->isUnread();
    }

    /**
     * Determine whether this post has never before been seen by the current user.
     */
    public function isNew(): bool
    {
        return $this->userState && !$this->userState->last_read_at;
    }

    /**
     * Get the Turbo Streams that should be sent when this post is updated.
     */
    public function streamUpdated(): array
    {
        $streams = [
            TurboStream::replace(new Components\PostListItem($this)),
            TurboStream::replace(new Components\PostCard($this)),
            TurboStream::replace(new Components\PostFull($this)),
            TurboStream::replace(new Components\PostSidebar($this)),
        ];

        if ($this->is_pinned) {
            $streams[] = TurboStream::replace(new Components\PinnedPost($this));
        }

        return $streams;
    }

    /**
     * Get the Turbo Streams that should be sent when this post is removed.
     */
    public function streamRemoved(): array
    {
        $streams = [
            TurboStream::remove(new Components\PostListItem($this)),
            TurboStream::remove(new Components\PostCard($this)),
        ];

        if ($this->is_pinned) {
            $streams[] = TurboStream::remove(new Components\PinnedPost($this));
        }

        return $streams;
    }

    public function getPerPage(): int
    {
        return config('waterhole.forum.posts_per_page', $this->perPage);
    }

    public function getRouteKey(): string
    {
        return $this->id . ($this->slug ? '-' . $this->slug : '');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->select('*')->whereKey(explode('-', $value)[0])->firstOrFail();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.show', ['post' => $this]),
        )->shouldCache();
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.edit', ['post' => $this]),
        )->shouldCache();
    }

    protected function unreadUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->isNew()) {
                    return $this->url;
                }

                $fragment = $this->unread_comments_count ? '#unread' : '#bottom';

                return $this->urlAtIndex($this->comment_count - $this->unread_comments_count - 1) .
                    $fragment;
            },
        )->shouldCache();
    }

    public function reactionsUrl(ReactionType $reactionType): string
    {
        return route('waterhole.posts.reactions', [
            'post' => $this,
            'reactionType' => $reactionType,
        ]);
    }

    public function reactionSet(): ?ReactionSet
    {
        return $this->channel->postsReactionSet;
    }

    public function canModerate(?User $user): bool
    {
        return (bool) $user?->can('waterhole.post.moderate', $this);
    }
}
