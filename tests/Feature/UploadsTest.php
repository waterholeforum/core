<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Waterhole\Models\Upload;

beforeEach(function () {
    Storage::fake(config('waterhole.uploads.disk'));
});

test('uses image density to determine display dimensions', function () {
    $image = Image::createImage(400, 200)->setResolution(144, 144);
    $file = UploadedFile::fake()->createWithContent(
        'image.png',
        $image->encode(new PngEncoder())->toString(),
    );

    $upload = Upload::fromFile($file);

    expect($upload)->width->toBe(200)->height->toBe(100);
});

test('keeps pixel dimensions for standard-density images', function () {
    $file = UploadedFile::fake()->image('image.png', 400, 200);

    $upload = Upload::fromFile($file);

    expect($upload)->filename->toEndWith('.png')->width->toBe(400)->height->toBe(200);
});

test('stores files that browsers may execute as plain text', function (
    string $name,
    string $content,
) {
    $upload = Upload::fromFile(UploadedFile::fake()->createWithContent($name, $content));

    expect($upload)->filename->toEndWith('.txt')->type->toBe('text/plain');
    Storage::disk(config('waterhole.uploads.disk'))->assertExists('uploads/' . $upload->filename);
})->with([
    'html' => ['page.html', '<!doctype html><script>alert(1)</script>'],
    'svg' => [
        'image.svg',
        '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
    ],
    'xml' => [
        'feed.rss',
        '<?xml version="1.0"?><rss><script xmlns="http://www.w3.org/1999/xhtml">alert(1)</script></rss>',
    ],
]);
