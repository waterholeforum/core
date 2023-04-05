<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Waterhole\Events\NewPost;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\NotificationContent;
use Waterhole\Models\Concerns\Reactable;
use Waterhole\Notifications\Mention;
use Waterhole\Scopes\PostVisibleScope;
use Waterhole\View\Components;
use Waterhole\View\TurboStream;

/**
 * @property int $id
 * @property int $channel_id
 * @property ?int $user_id
 * @property ?string $title
 * @property ?string $slug
 * @property ?\Carbon\Carbon $created_at
 * @property ?\Carbon\Carbon $edited_at
 * @property ?\Carbon\Carbon $last_activity_at
 * @property int $comment_count
 * @property int $score
 * @property bool $is_locked
 * @property ?int $answer_id
 * @property-read Channel $channel
 * @property-read ?User $user
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 * @property-read \Illuminate\Database\Eloquent\Collection $unreadComments
 * @property-read \Illuminate\Database\Eloquent\Collection $tags
 * @property-read ?Comment $lastComment
 * @property-read ?PostUser $userState
 * @property-read ?Comment $answer
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $unread_url
 */
class Post extends Model
{
    use Followable;
    use HasBody;
    use Reactable;
    use HasUserState;
    use NotificationContent;

    public const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    public static function booted()
    {
        static::addGlobalScope('visible', new PostVisibleScope(fn() => Auth::user()));

        static::creating(function (Post $post) {
            $post->last_activity_at ??= now();
        });

        static::created(function (self $post) {
            broadcast(new NewPost($post))->toOthers();

            // When a new post is created, send notifications to mentioned users.
            $users = $post->mentions
                ->except($post->user_id)
                ->filter(function (User $user) use ($post) {
                    return Post::withGlobalScope('visible', new PostVisibleScope($user))
                        ->whereKey($post->id)
                        ->exists();
                });

            $post->usersWereMentioned($users);

            Notification::send($users, new Mention($post));
        });

        // Delete comments one at a time to trigger event listeners.
        static::deleting(function (self $post) {
            $post
                ->comments()
                ->lazy()
                ->each->delete();
        });

        static::saving(function (self $post) {
            $sign = $post->score <=> 0;
            $seconds = ($post->created_at ?: now())->unix() - 1134028003;
            $post->hotness = round($sign * log10(max(abs($post->score), 1)) + $seconds / 45000, 10);
        });
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
        $query->leftJoinRelation('userState')->selectSub(
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
        return $this->belongsTo(Channel::class);
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
        return $this->hasMany(Comment::class)->withoutGlobalScope('visible');
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
        $this->last_activity_at =
            $this->comments()
                ->latest()
                ->value('created_at') ?:
            $this->created_at;
        $this->comment_count = $this->comments()->count();

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
        return [
            TurboStream::replace(new Components\PostListItem($this)),
            TurboStream::replace(new Components\PostCardsItem($this)),
            TurboStream::replace(new Components\PostFull($this)),
            TurboStream::replace(new Components\PostActions($this)),
        ];
    }

    /**
     * Get the Turbo Streams that should be sent when this post is removed.
     */
    public function streamRemoved(): array
    {
        return [
            TurboStream::remove(new Components\PostListItem($this)),
            TurboStream::remove(new Components\PostCardsItem($this)),
        ];
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
        return $this->whereKey(explode('-', $value)[0])->firstOrFail();
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.show', ['post' => $this]),
        )->shouldCache();
    }

    public function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.edit', ['post' => $this]),
        )->shouldCache();
    }

    public function unreadUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $fragment = match (true) {
                    $this->isNew() => '',
                    (bool) $this->unread_comments_count => '#unread',
                    default => '#bottom',
                };

                return $this->urlAtIndex($this->comment_count - $this->unread_comments_count - 1) .
                    $fragment;
            },
        )->shouldCache();
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function reactionSet(): ?ReactionSet
    {
        return $this->channel->postsReactionSet;
    }
}
