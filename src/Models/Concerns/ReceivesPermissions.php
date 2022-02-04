<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Permission;

/**
 * Methods to manage permissions that are granted to a model.
 *
 * This trait is distinct from `HasPermissions` in that it is for models that
 * take action (users and groups), rather than models that can be acted *upon*.
 *
 * @property-read \Waterhole\Models\PermissionCollection $permissions
 */
trait ReceivesPermissions
{
    public static function bootReceivesPermissions(): void
    {
        // Ensure model deletion cascades to permission records.
        static::deleted(function (self $model) {
            $model->permissions()->delete();
        });
    }

    /**
     * Relationship with the permission records granted to this model.
     */
    public function permissions(): MorphMany
    {
        return $this->morphMany(Permission::class, 'recipient');
    }

    /**
     * Save the permissions to the database.
     */
    public function savePermissions(?array $grid): void
    {
        $this->permissions()->delete();

        if (! $grid) {
            return;
        }

        $this->permissions()->createMany(
            collect($grid)->flatMap(function ($abilities, $scope) {
                [$type, $id] = explode(':', $scope) + [null, null];

                return collect($abilities)->filter()->map(fn($v, $ability) => [
                    'scope_type' => $type,
                    'scope_id' => $id,
                    'ability' => $ability,
                ])->values();
            })
        );
    }
}
