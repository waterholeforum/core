<?php

namespace Waterhole\Models\Attributes;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image as ImageObject;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use function Waterhole\is_absolute_url;

class FileAttribute
{
    public function __construct(
        protected Model $model,
        protected string $attribute,
        protected string $directory,
        protected ?string $disk = null,
        protected ?Closure $encodeImage = null,
    ) {
        $this->disk ??= config('waterhole.uploads.disk');
    }

    public function upload(File|UploadedFile $file): void
    {
        if ($this->encodeImage && $this->isImageFile($file)) {
            $this->storeImage(Image::read($file), $this->encodeImage);
        }

        $this->storeRawFile($file);
    }

    public function uploadImage(ImageObject $image): void
    {
        $encoder = $this->encodeImage ?? fn(ImageObject $image) => $image->toPng();

        $this->storeImage($image, $encoder);
    }

    public function remove(): void
    {
        $value = $this->model->{$this->attribute};

        if ($value) {
            Storage::disk($this->disk)->delete($this->directory . '/' . $value);
            $this->model->update([$this->attribute => null]);
        }
    }

    public function url(): ?string
    {
        $value = $this->model->{$this->attribute};

        if (!$value) {
            return null;
        }

        if (is_absolute_url($value)) {
            return $value;
        }

        return Storage::disk($this->disk)->url($this->directory . '/' . $value);
    }

    protected function storeRawFile(File|UploadedFile $file): void
    {
        $this->storeFileContents($file->getContent(), $file->extension());
    }

    protected function storeImage(ImageObject $image, Closure $encode): void
    {
        if (extension_loaded('exif')) {
            $image->orient();
        }

        /** @var EncodedImageInterface $encodedImage */
        $encodedImage = $encode($image);

        $this->storeFileContents($encodedImage, Str::after($encodedImage->mediaType(), '/'));
    }

    protected function storeFileContents($contents, string $extension): void
    {
        $this->remove();

        $filename = Str::random() . '.' . $extension;
        $this->model->update([$this->attribute => $filename]);

        Storage::disk($this->disk)->put($this->directory . '/' . $filename, $contents);
    }

    protected function isImageFile(File|UploadedFile $file): bool
    {
        return app('image')->driver()->supports($file->getMimeType());
    }
}
