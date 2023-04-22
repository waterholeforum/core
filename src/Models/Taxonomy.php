<?php

namespace Waterhole\Models;

use Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Waterhole\Models\Concerns\HasPermissions;

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

    public function editUrl(): Attribute
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
