<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as ImageObject;

trait HasIcon
{
    use HasImageAttributes;

    private static function iconRules(): array
    {
        return [
            'icon' => ['array:type,emoji,svg,file'],
            'icon.type' => ['nullable', 'in:emoji,svg,file'],
            'icon.file' => ['nullable', 'image'],
        ];
    }

    public function getIconFileAttribute(): ?string
    {
        return str_starts_with($this->icon, 'file:') ? substr($this->icon, 5) : null;
    }

    public function setIconFileAttribute(?string $value): void
    {
        $this->icon = $value ? 'file:'.$value : null;
    }

    public function saveIcon(array $icon)
    {
        if (empty($icon['type'])) {
            $this->removeImage('icon_file', 'icons');
            $this->icon = null;
            $this->save();
            return;
        }

        if ($icon['type'] === 'file') {
            if ($icon['file'] ?? null instanceof UploadedFile) {
                $this->uploadImage(
                    Image::make($icon['file']),
                    'icon_file',
                    'icons',
                    function (ImageObject $image) {
                        return $image->fit(50)->encode('png');
                    }
                );
            }
        } else {
            $this->icon = $icon['type'].':'.($icon[$icon['type']] ?? '');
            $this->save();
        }
    }
}
