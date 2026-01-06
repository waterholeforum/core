<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Upload extends Model
{
    use Prunable;

    public static function fromFile(File|UploadedFile $file): static
    {
        $attributes = [
            'filename' => $file->hashName(),
            'type' => $file->getMimeType(),
        ];

        if (str_starts_with($attributes['type'], 'image/')) {
            $image = Image::make($file);
            $attributes['width'] = $image->width();
            $attributes['height'] = $image->height();
        }

        Storage::disk(config('waterhole.uploads.disk'))->putFile('uploads', $file);

        // @phpstan-ignore-next-line
        return new static($attributes);
    }

    protected static function booted(): void
    {
        static::deleted(function (self $upload) {
            Storage::disk(config('waterhole.uploads.disk'))->delete('uploads/' . $upload->filename);
        });
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'content', 'attachments');
    }

    public function comments(): MorphToMany
    {
        return $this->morphedByMany(Comment::class, 'content', 'attachments');
    }

    public function prunable(): Builder
    {
        return static::whereNotExists(function ($query) {
            $query
                ->select('*')
                ->from('attachments')
                ->whereColumn('upload_id', 'id');
        });
    }
}
