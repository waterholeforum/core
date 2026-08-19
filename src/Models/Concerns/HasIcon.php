<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Image;
use Waterhole\Models\Attributes\FileAttribute;

/**
 * Methods to manage a model's `icon` attribute.
 *
 * The `icon` attribute is stored as `type:value`, where `type` is one of the
 * following:
 *
 * - `emoji`, where `value` is an emoji character (eg. `emoji:😊`)
 * - `svg`, where `value` is the name of a Blade Icon, optionally followed by
 *   a hex color (eg. `svg:tabler-heart` or `svg:tabler-heart:ff0000`)
 * - `file`, where `value` is the path to an image file
 *
 * @property string $icon
 * @property ?string $icon_file The path to the icon file, if the icon is the
 *   `file` type.
 */
trait HasIcon
{
    use HasFileAttributes;

    /**
     * Save the icon using input from an <x-waterhole::icon-picker> component.
     */
    public function saveIcon(array $icon): void
    {
        if (empty($icon['type'])) {
            $this->iconFile()->remove();
            $this->icon = null;
            $this->save();

            return;
        }

        if ($icon['type'] === 'file') {
            $file = $icon['file'] ?? null;

            if ($file instanceof UploadedFile) {
                $this->iconFile()->upload($file);
            }
        } else {
            $this->iconFile()->remove();
            $this->icon = $icon['type'] . ':' . ($icon[$icon['type']] ?? '');

            if ($icon['type'] === 'svg' && !empty($icon['color'])) {
                $this->icon .= ':' . ltrim($icon['color'], '#');
            }

            $this->save();
        }
    }

    public function iconFile(): FileAttribute
    {
        return $this->fileAttribute(
            attribute: 'icon_file',
            directory: 'icons',
            encodeImage: fn(Image $image) => $image->scaleDown(50, 50)->encode(new PngEncoder()),
        );
    }

    public function getIconFileAttribute(): ?string
    {
        return str_starts_with($this->icon ?? '', 'file:') ? substr($this->icon, 5) : null;
    }

    public function setIconFileAttribute(?string $value): void
    {
        $this->attributes['icon'] = $value ? 'file:' . $value : null;
    }
}
