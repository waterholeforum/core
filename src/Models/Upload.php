<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * @property int $id
 * @property null|int $user_id
 * @property string $filename
 * @property null|string $type
 * @property null|int $width
 * @property null|int $height
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection $posts
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 */
class Upload extends Model
{
    use Prunable;

    public static function fromFile(File|UploadedFile $file): static
    {
        $attributes = [
            'filename' => $file->hashName(),
            'type' => $file->getMimeType(),
        ];

        // Store types that browsers can execute as plain text so they are inert.
        if (Str::is([
            '*html',
            '*/xml',
            '*+xml',
            '*javascript',
            '*ecmascript',
            '*php',
        ], $attributes['type'])) {
            $attributes['filename'] = Str::beforeLast($attributes['filename'], '.') . '.txt';
            $attributes['type'] = 'text/plain';
        }

        $manager = app(ImageManager::class);

        if ($manager->driver->supports($attributes['type'])) {
            $image = $manager->decode($file);
            $resolution = $image->resolution()->perInch();

            $attributes['width'] = static::displayDimension($image->width(), $resolution->x());
            $attributes['height'] = static::displayDimension($image->height(), $resolution->y());
        }

        Storage::disk(config('waterhole.uploads.disk'))->putFileAs(
            'uploads',
            $file,
            $attributes['filename'],
        );

        // @phpstan-ignore-next-line
        return new static($attributes);
    }

    private static function displayDimension(int $pixels, float $dpi): int
    {
        // The image drivers report 96 DPI when density metadata is absent.
        return $dpi > 96 ? max(1, (int) round(($pixels * 72) / $dpi)) : $pixels;
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
            $query->select('*')->from('attachments')->whereColumn('upload_id', 'id');
        });
    }
}
