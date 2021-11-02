<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Waterhole\Models\Concerns\Followable;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasUserState;
use Waterhole\Models\Concerns\HasVisibility;
use Waterhole\Views\Components;
use Waterhole\Views\TurboStream;

class Post extends Model
{
    use HasLikes;
    use HasBody;
    use HasVisibility;
    use HasUserState;
    use Followable;

    const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    protected $withCount = ['unreadComments'];

    public static function byUser(User $user, array $attributes = []): static
    {
        return new static(array_merge(['user_id' => $user->id], $attributes));
    }

    public static function booting()
    {
        static::creating(function (Post $post) {
            $post->last_activity_at ??= now();
        });
    }

    public function scopeUnread(Builder $query)
    {
        $query->whereDoesntHave('userState', function ($query) {
            $query->whereColumn('last_read_at', '>', 'last_activity_at');
        });
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

    public function unreadComments(): HasMany
    {
        return $this->comments()
            ->whereRaw(
                'created_at > COALESCE((select last_read_at from post_user where post_id = comments.post_id and post_user.user_id = ?), 0)',
                [Auth::id()]
            );
    }

    public function lastComment(): HasOne
    {
        return $this->hasOne(Comment::class)->latestOfMany();
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
        $this->last_activity_at = $this->comments()->latest()->value('created_at') ?: $this->created_at;
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
            ->firstOrFail();
    }

    public function isUnread(): bool
    {
        return $this->userState && $this->last_activity_at > $this->userState->last_read_at;
    }

    public function isRead(): bool
    {
        return $this->userState && ! $this->isUnread();
    }

    public function isNew(): bool
    {
        return $this->userState && ! $this->userState->last_read_at;
    }

    public function getPerPage(): int
    {
        return config('waterhole.forum.posts_per_page', $this->perPage);
    }

    public function streamUpdated(): array
    {
        return [
            TurboStream::replace(new Components\PostListItem($this)),
            TurboStream::replace(new Components\PostCardsItem($this)),
            TurboStream::replace(new Components\PostFull($this)),
        ];
    }

    public function streamRemoved(): array
    {
        return [
            TurboStream::remove(new Components\PostListItem($this)),
            TurboStream::remove(new Components\PostCardsItem($this)),
        ];
    }
}
