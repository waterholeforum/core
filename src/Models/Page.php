<?php

namespace Waterhole\Models;

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

    public function getUrlAttribute(): string
    {
        return route('waterhole.page', ['page' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.cp.structure.pages.edit', ['page' => $this]);
    }
}
