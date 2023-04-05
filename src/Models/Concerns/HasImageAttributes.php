<?php

namespace Waterhole\Models\Concerns;

use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;

use function Waterhole\is_absolute_url;

/**
 * Methods to associate uploaded images with a model.
 *
 * These methods are all private. The model will usually want to expose public
 * methods for each image attribute, eg. `avatar`, `uploadAvatar`,
 * and `removeAvatar`, wrapping around these private methods.
 */
trait HasImageAttributes
{
    /**
     * Upload an image and set it to an attribute.
     */
    private function uploadImage(
        Image $image,
        string $attribute,
        string $directory,
        Closure $encode,
    ): static {
        $this->removeImage($attribute, $directory);

        if (extension_loaded('exif')) {
            $image->orientate();
        }

        $encodedImage = $encode($image);
        $ext = Str::after($encodedImage->mime, '/');

        $this->update([$attribute => Str::random() . '.' . $ext]);

        Storage::disk('public')->put($directory . '/' . $this->$attribute, $encodedImage);

        return $this;
    }

    /**
     * Remove the image for an attribute.
     */
    private function removeImage(string $attribute, string $directory): static
    {
        if ($this->$attribute) {
            Storage::disk('public')->delete($directory . '/' . $this->$attribute);

            $this->update([$attribute => null]);
        }

        return $this;
    }

    /**
     * Resolve the public URL for a file path.
     *
     * If the file path is already an absolute URL, it will not be changed.
     * Otherwise, it is treated as a relative path on the `public` storage disk.
     */
    private function resolvePublicUrl(?string $value, string $directory): ?string
    {
        if (!$value) {
            return null;
        }

        if (is_absolute_url($value)) {
            return $value;
        }

        return Storage::disk('public')->url($directory . '/' . $value);
    }
}
