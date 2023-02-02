<?php

namespace Waterhole\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Facades\Image;
use Waterhole\Formatter\FormatUploads;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                File::types(config('waterhole.uploads.allowed_mimetypes'))->max(
                    config('waterhole.uploads.max_upload_size'),
                ),
            ],
        ]);

        $file = $request->file('file');

        $attributes = [
            'filename' => $file->hashName(),
            'type' => $file->getMimeType(),
        ];

        if (str_starts_with($attributes['type'], 'image/')) {
            $image = Image::make($file);
            $attributes['width'] = $image->width();
            $attributes['height'] = $image->height();
        }

        $upload = $request
            ->user()
            ->uploads()
            ->create($attributes);

        Storage::disk('public')->putFile('uploads', $file);

        return ['url' => FormatUploads::PROTOCOL . $upload->filename];
    }
}
