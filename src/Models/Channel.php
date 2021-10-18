<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\Image;
use Waterhole\Actions\Editable;
use Waterhole\Extend\FeedSort;
use Waterhole\Models\Concerns\HasImageAttributes;

class Channel extends Model implements Editable
{
    use HasImageAttributes;

    public $timestamps = false;

    protected $casts = [
        'sorts' => 'json',
        'layouts' => 'json',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function unreadPosts(): HasMany
    {
        return $this->posts()->unread();
    }

    public function userState(User $user = null): HasOne
    {
        $userId = $user ? $user->id : Auth::id();

        $relation = $this->hasOne(ChannelUser::class);
        $relation->where($relation->qualifyColumn('user_id'), $userId);

        if ($userId) {
            $relation->withDefault(['user_id' => $userId]);
        }

        return $relation;
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->resolvePublicUrl($this->cover, 'channel-covers');
    }

    public function removeCover(): void
    {
        $this->removeImage('cover', 'channel-covers');
    }

    public function uploadCover(Image $image): void
    {
        $this->uploadImage($image, 'cover', 'channel-covers', function (Image $image) {
            return $image->crop(1000, 300)->encode('jpg');
        });
    }

    public function getUrlAttribute(): string
    {
        return route('waterhole.channels.show', ['channel' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.channels.edit', ['channel' => $this]);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->icon.' '.$this->name;
    }

    public static function rules(Channel $channel = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('channels')->ignore($channel)],
            'emoji' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'hide_sidebar' => ['nullable', 'boolean'],
            'sandbox' => ['nullable', 'boolean'],
            'default_layout' => ['in:list,cards'],
            'sorts' => ['required_with:custom_sorts', 'array'],
            'sorts.*' => ['string', 'distinct', Rule::in(FeedSort::getInstances()->map->handle())],
            'default_sort' => ['required_with:custom_sorts', 'in_array:sorts.*'],
        ];
    }
}
