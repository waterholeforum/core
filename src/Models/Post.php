<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Waterhole\Actions\Deletable;
use Waterhole\Actions\Editable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasVisibility;

class Post extends Model implements Deletable, Editable
{
    use HasLikes;
    use HasBody;
    use HasVisibility;

    const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'last_comment_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public static function byUser(User $user, array $attributes = []): static
    {
        return new static(array_merge(['user_id' => $user->id], $attributes));
    }

    protected function getLastReadAtStatement(User $user): array
    {
        return [
            'GREATEST(
                COALESCE((select last_read_at from post_user where post_id = posts.id and post_user.user_id = 1), 0),
                COALESCE((select marked_read_at from channel_user where channel_id = posts.channel_id and channel_user.user_id = 1), 0),
                COALESCE(?, 0)
            )',
            [$user->marked_read_at]
        ];
    }

    public function scopeWithUnreadCount(Builder $query)
    {
        if (! $user = Auth::user()) {
            return;
        }

        [$lastReadAt, $bindings] = $this->getLastReadAtStatement($user);

        $query->selectRaw("GREATEST(COALESCE(created_at, 0), COALESCE(last_comment_at, 0)) > $lastReadAt as is_unread", $bindings)
            ->withCount(['comments as unread_comments_count' => function ($query) use ($lastReadAt, $bindings) {
                $query->whereRaw("created_at > $lastReadAt", $bindings);
            }]);
    }

    public function scopeUnread(Builder $query)
    {
        [$lastReadAt, $bindings] = $this->getLastReadAtStatement(Auth::user());

        $query->whereRaw("GREATEST(COALESCE(created_at, 0), COALESCE(last_comment_at, 0)) > $lastReadAt", $bindings);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function lastComment(): HasOne
    {
        return $this->hasOne(Comment::class)->latestOfMany();
    }

    public function userState(User $user = null): HasOne
    {
        $userId = $user ? $user->id : Auth::id();

        $relation = $this->hasOne(PostUser::class)->where('post_user.user_id', $userId);

        if ($userId) {
            $relation->withDefault(['user_id' => $userId]);
        }

        return $relation;
    }

    public function getUrlAttribute(): string
    {
        return route('waterhole.posts.show', ['post' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.posts.edit', ['post' => $this]);
    }

    public function url(array $options = []): string
    {
        $params = ['post' => $this];

        if ($index = $options['index'] ?? null) {
            $params['page'] = floor($index / (new Comment)->getPerPage()) + 1;
        }

        return route('waterhole.posts.show', $params);
    }

    public function wasEdited(): static
    {
        $this->edited_at = now();

        return $this;
    }

    public function refreshCommentMetadata(): static
    {
        $this->last_comment_at = $this->comments()->latest()->value('created_at');
        $this->comment_count = $this->comments()->count();

        return $this;
    }

    public static function rules(Post $post = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];

        if (! $post) {
            $rules['channel_id'] = ['required', Rule::exists(Channel::class, 'id')];
        }

        return $rules;
    }

    public function getRouteKey()
    {
        return $this->id.($this->slug ? '-'.$this->slug : '');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this
            ->where('id', explode('-', $value)[0])
            ->visibleTo(Auth::user())
            ->select('posts.*')
            ->withUnreadCount()
            ->firstOrFail();
    }
}
