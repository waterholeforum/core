<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Concerns\HasPermissions;

/**
 * @property int $id
 * @property string $name
 * @property bool $allow_multiple
 * @property bool $is_required
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection $tags
 * @property-read string $edit_url
 */
class Taxonomy extends Model
{
    use HasPermissions;

    protected static function booted(): void
    {
        static::addGlobalScope(function ($query) {
            if ($ids = static::allPermitted(Auth::user())) {
                $query->whereKey($ids);
            }
        });
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class)->orderBy('name');
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.taxonomies.edit', ['taxonomy' => $this]),
        )->shouldCache();
    }

    public function abilities(): array
    {
        return ['view', 'assign-tags'];
    }

    public function defaultAbilities(): array
    {
        return ['view'];
    }
}
