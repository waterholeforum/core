<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Intervention\Image\Image;
use Waterhole\Models\Concerns\HasImageAttributes;

class Channel extends Model
{
    use HasImageAttributes;

    public $timestamps = false;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
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
        ];
    }
}
