<?php

namespace Waterhole\Models;

use Illuminate\Validation\Rule;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

class Page extends Model
{
    use Structurable;
    use HasPermissions;
    use HasBody;
    use HasIcon;

    public $timestamps = false;

    public function getUrlAttribute(): string
    {
        return route('waterhole.page', ['page' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.pages.edit', ['page' => $this]);
    }

    public static function rules(Page $page = null): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')->ignore($page)],
            'body' => ['required', 'string'],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
