<?php

namespace Waterhole\Models;

use Illuminate\Validation\Rule;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\ValidatesData;

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
    use ValidatesData;

    public $timestamps = false;

    public function getUrlAttribute(): string
    {
        return route('waterhole.page', ['page' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.pages.edit', ['page' => $this]);
    }

    public static function rules(Page $instance = null): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')->ignore($instance)],
            'body' => ['required', 'string'],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
