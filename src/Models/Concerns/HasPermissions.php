<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Permission;

/**
 * Methods to manage permissions on a model.
 *
 * This trait is distinct from `ReceivesPermissions` in that it is for models
 * that can be acted *upon*, rather than models that take the action (users
 * and groups).
 */
trait HasPermissions
{
    public static function bootHasPermissions(): void
    {
        // Ensure model deletion cascades to permission records.
        static::deleted(function (self $model) {
            $model->permissions()->delete();
        });
    }

    /**
     * Relationship with the permission records pertaining to this model.
     */
    public function permissions(): MorphMany
    {
        return $this->morphMany(Permission::class, 'scope');
    }

    /**
     * A list of abilities that can be applied to this model.
     */
    public function abilities(): array
    {
        return ['view'];
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
            collect($grid)->flatMap(function ($abilities, $recipient) {
                [$type, $id] = explode(':', $recipient) + [null, null];

                return collect($abilities)->filter()->map(fn($v, $ability) => [
                    'recipient_type' => $type,
                    'recipient_id' => $id,
                    'ability' => $ability,
                ])->values();
            })
        );
    }
}
