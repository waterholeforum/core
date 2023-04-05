<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $url
 * @property string $edit_url
 */
class Page extends Model
{
    use HasBody;
    use HasIcon;
    use HasPermissions;
    use Structurable;

    public $timestamps = false;

    protected static function booting(): void
    {
        static::creating(function (self $model) {
            $model->slug ??= Str::slug($model->name);
        });
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.page', ['page' => $this]),
        )->shouldCache();
    }

    public function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.structure.pages.edit', ['page' => $this]),
        )->shouldCache();
    }
}
