<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use LogicException;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Permissions\PermissionRepository;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property int $position
 * @property string $content_type
 * @property int $content_id
 * @property bool $is_listed
 * @property-read Model $content
 */
class Structure extends Model
{
    use HasPermissions;
    use HasRecursiveRelationships;

    protected $table = 'structure';

    public $timestamps = false;

    protected $casts = [
        'is_listed' => 'bool',
    ];

    protected static function booting()
    {
        $flushPermissions = fn() => app()->resolved(PermissionRepository::class)
            ? app(PermissionRepository::class)->flush()
            : null;

        static::saved($flushPermissions);
        static::deleted($flushPermissions);

        static::saving(function (self $node) {
            $node->validateParent();
        });

        static::deleting(function (self $node) {
            if ($node->children()->withoutGlobalScopes()->exists()) {
                throw new LogicException('Cannot delete a structure node with children.');
            }
        });
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }

    public function enableCycleDetection(): bool
    {
        return true;
    }

    public function scopeInSiblingOrder(Builder $query): void
    {
        $query->orderBy('position')->orderBy($this->getQualifiedKeyName());
    }

    public function scopeListed(Builder $query): void
    {
        $query->where(
            'is_listed',
            true,
        )->whereDoesntHave('ancestors', fn(Builder $query) => $query->withoutGlobalScopes()->where(
            'is_listed',
            false,
        ));
    }

    public function isListed(): bool
    {
        return (
            $this->is_listed
            && !$this->ancestors()->withoutGlobalScopes()->where('is_listed', false)->exists()
        );
    }

    public function canHaveChildren(): bool
    {
        return in_array(
            $this->content_type,
            [
                (new Channel())->getMorphClass(),
                (new Page())->getMorphClass(),
            ],
            true,
        );
    }

    public static function flattenTree(Collection $tree): Collection
    {
        $nodes = collect();
        $flatten = function (Collection $children) use (&$flatten, $nodes) {
            foreach ($children as $child) {
                $nodes->push($child);
                $flatten($child->children);
            }
        };
        $flatten($tree);

        return $nodes;
    }

    public static function nextPosition(?int $parentId): int
    {
        $position = static::withoutGlobalScopes()->where('parent_id', $parentId)->max('position');

        return $position === null ? 0 : $position + 1;
    }

    public function promoteChildren(): void
    {
        DB::transaction(function () {
            $children = $this->children()->withoutGlobalScopes()->inSiblingOrder()->get();

            if ($children->isEmpty()) {
                return;
            }

            static::withoutGlobalScopes()
                ->where('parent_id', $this->parent_id)
                ->where('position', '>', $this->position)
                ->increment('position', $children->count() - 1);

            $position = $this->position;

            $children->each(function (self $child) use (&$position) {
                $child->update([
                    'parent_id' => $this->parent_id,
                    'position' => $position++,
                ]);
            });
        });
    }

    private function validateParent(): void
    {
        if ($this->parent_id === null) {
            return;
        }

        $parent = static::withoutGlobalScopes()->find($this->parent_id);

        if (
            !$parent?->canHaveChildren()
            || $this->exists
            && $this
                ->descendantsAndSelf()
                ->withoutGlobalScopes()
                ->whereKey($parent->getKey())
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'parent_id' => __('validation.exists', ['attribute' => 'parent']),
            ]);
        }
    }
}
