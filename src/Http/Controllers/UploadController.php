<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Models\Upload;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
    }

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

        $upload = Upload::fromFile($request->file('file'));

        $request
            ->user()
            ->uploads()
            ->save($upload);

        return ['url' => FormatUploads::PROTOCOL . $upload->filename];
    }
}
