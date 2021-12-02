<?php

namespace Waterhole\Models\Concerns;

use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;

trait HasImageAttributes
{
    private function resolvePublicUrl(?string $value, string $directory): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('|^https?://|', $value)) {
            return $value;
        }

        return Storage::disk('public')->url($directory.'/'.$value);
    }

    private function removeImage(string $attribute, string $directory): static
    {
        if ($this->$attribute) {
            Storage::disk('public')->delete($directory.'/'.$this->$attribute);
            $this->$attribute = null;
            $this->save();
        }

        return $this;
    }

    private function uploadImage(Image $image, string $attribute, string $directory, Closure $encode): static
    {
        $this->removeImage($attribute, $directory);

        if (extension_loaded('exif')) {
            $image->orientate();
        }

        $encodedImage = $encode($image);

        $this->$attribute = Str::random().'.'.Str::after($encodedImage->mime, '/');
        $this->save();
        Storage::disk('public')->put($directory.'/'.$this->$attribute, $encodedImage);

        return $this;
    }
}
