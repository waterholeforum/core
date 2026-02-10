<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Waterhole\Models\Concerns\Bookmarkable;
use Waterhole\Models\Support\MorphTypeCache;

/**
 * @property int $id
 * @property int $user_id
 * @property string $content_type
 * @property int $content_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 * @property-read null|\Illuminate\Database\Eloquent\Model $content
 */
class Bookmark extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all bookmarkable model classes from the morph map.
     *
     * @return array<int, class-string<\Illuminate\Database\Eloquent\Model>>
     */
    public static function bookmarkableClasses(): array
    {
        return MorphTypeCache::forTrait(Bookmarkable::class);
    }

    /**
     * Get eager-load definitions keyed by bookmarkable model class.
     *
     * @return array<class-string, array<int, string>>
     */
    public static function bookmarkableMorphWith(): array
    {
        return collect(self::bookmarkableClasses())
            ->mapWithKeys(function (string $class) {
                if (method_exists($class, 'bookmarkMorphWith')) {
                    return [$class => $class::bookmarkMorphWith()];
                }

                return [$class => []];
            })
            ->all();
    }

    /**
     * Scope to bookmarks where content is visible to the given user.
     */
    public function scopeVisible(Builder $query, ?User $user): void
    {
        if (!$user) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->whereBelongsTo($user);

        if (empty(($classes = self::bookmarkableClasses()))) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->whereHasMorph('content', $classes, function (Builder $query, string $type) use (
            $user,
        ) {
            if (method_exists($type, 'scopeVisible')) {
                $query->visible($user);
            }
        });
    }
}
