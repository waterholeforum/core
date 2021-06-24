<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Intervention\Image\Image;
use Waterhole\Actions\Deletable;
use Waterhole\Models\Concerns\HasImageAttributes;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasMentions;

class Post extends Model implements Deletable
{
    use HasLikes, HasMentions, HasImageAttributes;

    const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'last_comment_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

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

    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('last_read_at');
    }

    public function getCoverUrlAttribute()
    {
        return $this->resolvePublicUrl($this->cover, 'post-covers');
    }

    public function removeCover()
    {
        $this->removeImage('cover', 'post-covers');
    }

    public function uploadCover(Image $image)
    {
        $this->uploadImage($image, 'cover', 'post-covers', function (Image $image) {
            return $image->crop(1000, 300)->encode('jpg');
        });
    }

    public function getUrlAttribute()
    {
        return route('waterhole.posts.show', ['post' => $this]);
    }

    public function getEditUrlAttribute()
    {
        return route('waterhole.posts.edit', ['post' => $this]);
    }

    public static function rules(): array
    {
        return [
            'channel_id' => [Rule::exists(Channel::class, 'id')],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }
}
