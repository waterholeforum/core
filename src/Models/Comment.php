<?php

namespace Waterhole\Models;

use HotwiredLaravel\TurboLaravel\Models\Broadcasts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Waterhole\Database\Factories\CommentFactory;
use Waterhole\Events\NewComment;
use Waterhole\Models\Concerns\Approvable;
use Waterhole\Models\Concerns\Deletable;
use Waterhole\Models\Concerns\Flaggable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\NotificationContent;
use Waterhole\Models\Concerns\Reactable;
use Waterhole\Models\Concerns\ValidatesData;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewComment as NewCommentNotification;
use Waterhole\Scopes\CommentIndexScope;
use Waterhole\View\Components;
use Waterhole\View\TurboStream;
use function HotwiredLaravel\TurboLaravel\dom_id;

/**
 * @property int $id
 * @property int $post_id
 * @property null|int $parent_id
 * @property null|int $user_id
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property null|\Carbon\Carbon $edited_at
 * @property int $reply_count
 * @property int $score
 * @property-read Post $post
 * @property-read null|User $user
 * @property-read \Illuminate\Database\Eloquent\Collection $replies
 * @property-read null|Comment $parent
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $post_url
 */
class Comment extends Model
{
    use HasFactory;
    use HasBody;
    use Reactable;
    use HasRecursiveRelationships;
    use ValidatesData;
    use Broadcasts;
    use NotificationContent;
    use Deletable;
    use Approvable;
    use Flaggable;

    public const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    // Prevent recursion during serialization
    protected $hidden = ['parent'];

    protected static function booting(): void
    {
        static::addGlobalScope('visible', function ($query) {
            $user = auth()->user();

            if (app()->runningInConsole() && !app()->runningUnitTests() && !$user) {
                return;
            }

            $query->visible($user);
        });

        // Whenever a comment is created or deleted, we will update the metadata
        // (number of replies) of the post and any parent comment that this one
        // was made in reply to.
        $refreshMetadata = function (self $comment) {
            $comment->post->refreshCommentMetadata()->save();
            $comment->parent?->refreshReplyMetadata()->save();
        };

        static::created($refreshMetadata);
        static::deleted($refreshMetadata);
        static::restored($refreshMetadata);

        static::updated(function (self $comment) use ($refreshMetadata) {
            if ($comment->wasChanged('is_approved') && $comment->is_approved) {
                $refreshMetadata($comment);
            }
        });

        // By default, we calculate each comment's index (ie. how many comments
        // came before it) when querying comments. Since this is an expensive
        // thing to do, put it in a global scope so that it can be disabled.
        static::addGlobalScope(new CommentIndexScope());
    }

    protected static function booted(): void
    {
        // Register the listener to deliver created events after the HasBody
        // trait has been booted and has registered its listeners, to ensure
        // the body is processed before delivering @mention notifications etc.
        static::created(function (self $comment) {
            $comment->deliverCreatedEvents();
        });
    }

    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new();
    }

    protected function deliverCreatedEvents(): void
    {
        if (!$this->is_approved || !$this->post->is_approved) {
            return;
        }

        broadcast(new NewComment($this))->toOthers();

        // When a new comment is created, send notifications to mentioned
        // users as well as the user the comment is in reply to.
        $users = $this->mentions;

        if ($this->parent?->user) {
            $users->push($this->parent->user);
        }

        $users = $users->unique()->except($this->user_id);

        $this->post->usersWereMentioned($users);

        Notification::send($users, new Mention($this));

        // Send out a "new comment" notification to all followers of this post,
        // except for the user who made the comment.
        Notification::send(
            $this->post->followedBy->diff($users)->except($this->user_id),
            new NewCommentNotification($this),
        );
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class)->withoutGlobalScope('visible');
    }

    public function channel(): HasOneThrough
    {
        return $this->hasOneThrough(
            Channel::class,
            Post::class,
            'id',
            'id',
            'post_id',
            'channel_id',
        )->withTrashedParents();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }
    public function scopeVisible(Builder $query, ?User $user): void
    {
        // Remove the default visible global scope which scopes for the
        // currently authenticated user.
        $query->withoutGlobalScope('visible');

        // Ensure comments belong to a post which is visible to this user,
        // unless we are getting comments for a specific post, in which case
        // we assume the post visibility has already been checked.
        $hasWherePostId = collect($query->getQuery()->wheres)
            ->where('column', 'comments.post_id')
            ->isNotEmpty();

        if (!$hasWherePostId) {
            $query->whereHas('post', fn($query) => $query->visible($user));
        }

        $moderationScope = fn(Builder $query, array $channelIds) => $query->orWhereHas(
            'post',
            fn(Builder $query) => $query->whereIn('channel_id', $channelIds),
        );

        $this->applyApprovalVisibility($query, $user, $moderationScope);

        $this->applyDeletionVisibility($query, $user, $moderationScope);
    }

    /**
     * Determine whether this comment is unread by the current user.
     */
    public function isUnread(): bool
    {
        return $this->post->userState && $this->post->userState->last_read_at < $this->created_at;
    }

    /**
     * Determine whether this comment is read by the current user.
     */
    public function isRead(): bool
    {
        return $this->post->userState && !$this->isUnread();
    }

    /**
     * Determine whether this comment has been marked as the answer.
     */
    public function isAnswer(): bool
    {
        return $this->post->answer_id === $this->id;
    }

    /**
     * Mark this comment as having been edited just now.
     */
    public function markAsEdited(): static
    {
        $this->edited_at = now();

        return $this;
    }

    /**
     * Refresh the metadata about this comment's replies.
     */
    public function refreshReplyMetadata(): static
    {
        $this->reply_count = $this->replies()->count();

        return $this;
    }

    public function getPerPage(): int
    {
        return config('waterhole.forum.comments_per_page', $this->perPage);
    }

    /**
     * Get the Turbo Streams that should be sent when this comment is updated.
     */
    public function streamUpdated(): array
    {
        return [TurboStream::replace(new Components\CommentFull($this))];
    }

    /**
     * Get the Turbo Streams that should be sent when this comment is removed.
     */
    public function streamRemoved(): array
    {
        return [TurboStream::remove(new Components\CommentFrame($this))];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.comments.show', [
                'post' => $this->post,
                'comment' => $this,
            ]),
        )->shouldCache();
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.posts.comments.edit', [
                'post' => $this->post,
                'comment' => $this,
            ]),
        )->shouldCache();
    }

    protected function postUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (isset($this->index)) {
                    return $this->post->urlAtIndex($this->index) . '#' . dom_id($this);
                }

                return $this->post->url . '?comment=' . $this->id;
            },
        )->shouldCache();
    }

    public function reactionsUrl(ReactionType $reactionType): string
    {
        return route('waterhole.comments.reactions', [
            'comment' => $this,
            'reactionType' => $reactionType,
        ]);
    }

    public static function rules(?Comment $instance = null): array
    {
        return [
            'parent_id' => ['nullable', Rule::exists(Comment::class, 'id')],
            'body' => ['required', 'string'],
        ];
    }

    public function reactionSet(): ?ReactionSet
    {
        return $this->post->channel->commentsReactionSet;
    }

    public function canModerate(?User $user): bool
    {
        return (bool) $user?->can('waterhole.comment.moderate', $this);
    }

    public function flagUrl(): string
    {
        return $this->post_url;
    }
}
