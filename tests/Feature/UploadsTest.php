<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Waterhole\Models\Upload;

beforeEach(function () {
    Storage::fake(config('waterhole.uploads.disk'));
});

test('uses image density to determine display dimensions', function () {
    $image = Image::create(400, 200)->setResolution(144, 144);
    $file = UploadedFile::fake()->createWithContent('image.png', $image->toPng()->toString());

    $upload = Upload::fromFile($file);

    expect($upload)->width->toBe(200)->height->toBe(100);
});

test('keeps pixel dimensions for standard-density images', function () {
    $file = UploadedFile::fake()->image('image.png', 400, 200);

    $upload = Upload::fromFile($file);

    expect($upload)->width->toBe(400)->height->toBe(200);
});
