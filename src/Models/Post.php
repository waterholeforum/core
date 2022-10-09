<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Waterhole\Events\NewPost;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\NotificationContent;
use Waterhole\Models\Concerns\ValidatesData;
use Waterhole\Notifications\Mention;
use Waterhole\Scopes\CommentIndexScope;
use Waterhole\Scopes\PostVisibleScope;
use Waterhole\Views\Components;
use Waterhole\Views\TurboStream;

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
 * @property-read Channel $channel
 * @property-read ?User $user
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 * @property-read \Illuminate\Database\Eloquent\Collection $unreadComments
 * @property-read ?Comment $lastComment
 * @property-read ?PostUser $userState
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $unread_url
 */
class Post extends Model
{
    use Followable;
    use HasBody;
    use HasLikes;
    use HasUserState;
    use ValidatesData;
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
        });

        // When a new post is created, send notifications to mentioned users.
        // We have to use `saved` instead of `created` because the mentions are
        // synced to the database in the `saved` event, and `created` is always
        // run before `saved`.
        static::saved(function (Post $post) {
            if (!$post->wasRecentlyCreated) {
                return;
            }

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
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with the post's unread comments for the current user.
     */
    public function unreadComments(): HasMany
    {
        // This relationship is used to provide a count of unread comments for
        // each post in the post feed. Remove the `CommentIndexScope` as it is
        // not needed and causes performance to suffer in this context.
        return $this->comments()
            ->withoutGlobalScope(CommentIndexScope::class)
            ->whereRaw(
                'created_at > COALESCE((select last_read_at from post_user where post_id = comments.post_id and post_user.user_id = ?), 0)',
                [Auth::id()],
            );
    }

    /**
     * Relationship with the post's most recent comment.
     */
    public function lastComment(): HasOne
    {
        return $this->hasOne(Comment::class)->latestOfMany();
    }

    /**
     * Generate a URL for a particular comment index in this post.
     */
    public function url(int $index = 0): string
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

    public function getUrlAttribute(): string
    {
        return route('waterhole.posts.show', ['post' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.posts.edit', ['post' => $this]);
    }

    public function getUnreadUrlAttribute(): string
    {
        $fragment = match (true) {
            $this->isNew() => '',
            (bool) $this->unread_comments_count => '#unread',
            default => '#bottom',
        };

        return $this->url($this->comment_count - $this->unread_comments_count - 1) . $fragment;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static function rules(Post $instance = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];

        if (!$instance) {
            $rules['channel_id'] = ['required', Rule::exists(Channel::class, 'id')];
        }

        return $rules;
    }
}
