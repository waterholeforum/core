<?php

namespace Waterhole\Models;

use Illuminate\Validation\Rule;
use Waterhole\Models\Concerns\HasBody;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

class Page extends Model
{
    use Structurable;
    use HasPermissions;
    use HasBody;

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
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')->ignore($page)],
            'icon' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'permissions' => ['array'],
        ];
    }
}
