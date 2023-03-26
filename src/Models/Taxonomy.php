<?php

namespace Waterhole\Models;

use Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
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

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.cp.taxonomies.edit', ['taxonomy' => $this]);
    }

    public function getTranslatedNameAttribute(): string
    {
        return __(['waterhole.taxonomy-' . Str::kebab($this->name), $this->name]);
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
