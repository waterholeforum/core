<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReactionSet extends Model
{
    private static Collection $defaults;

    protected $casts = [
        'is_default_posts' => 'bool',
        'is_default_comments' => 'bool',
        'allow_multiple' => 'bool',
        'allow_custom' => 'bool',
    ];

    public function reactionTypes(): HasMany
    {
        return $this->hasMany(ReactionType::class)->orderBy('position');
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.reaction-sets.edit', ['reactionSet' => $this]),
        )->shouldCache();
    }

    private static function defaults(): Collection
    {
        return static::$defaults ??= static::query()
            ->where('is_default_posts', true)
            ->orWhere('is_default_comments', true)
            ->get();
    }

    public static function defaultPosts(): ?static
    {
        return static::defaults()->firstWhere('is_default_posts', true);
    }

    public static function defaultComments(): ?static
    {
        return static::defaults()->firstWhere('is_default_comments', true);
    }
}
