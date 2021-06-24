<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ReactionSet extends Model
{
    private static ?ReactionSet $defaultPosts;
    private static ?ReactionSet $defaultComments;

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

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.reaction-sets.edit', ['reactionSet' => $this]);
    }

    public static function defaultPosts(): ?static
    {
        return static::$defaultPosts ??= static::firstWhere('is_default_posts', true);
    }

    public static function defaultComments(): ?static
    {
        return static::$defaultComments ??= static::firstWhere('is_default_comments', true);
    }
}
