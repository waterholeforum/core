<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Permission;
use Waterhole\Models\PermissionCollection;
use Waterhole\Models\User;
use Waterhole\Scopes\PermittedScope;
use Waterhole\Waterhole;

/**
 * Methods to manage permissions on a model.
 *
 * This trait is distinct from `ReceivesPermissions` in that it is for models
 * that can be acted *upon*, rather than models that take the action (users
 * and groups).
 *
 * @property-read PermissionCollection $permissions
 */
trait HasPermissions
{
    public static function bootHasPermissions(): void
    {
        static::addGlobalScope(new PermittedScope());

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
     * A list of abilities to check by default when creating a model.
     */
    public function defaultAbilities(): array
    {
        return ['view'];
    }

    /**
     * Save the permissions to the database.
     */
    public function savePermissions(?array $grid): void
    {
        $this->permissions()->delete();

        if (!$grid) {
            return;
        }

        $this->permissions()->createMany(
            collect($grid)->flatMap(function ($abilities, $recipient) {
                [$type, $id] = explode(':', $recipient) + [null, null];

                return collect($abilities)
                    ->filter()
                    ->map(
                        fn($v, $ability) => [
                            'recipient_type' => $type,
                            'recipient_id' => $id,
                            'ability' => $ability,
                        ],
                    )
                    ->values();
            }),
        );
    }

    public function isPublic(string $ability = 'view'): bool
    {
        return Waterhole::permissions()
            ->guest()
            ->ability($ability)
            ->scope(static::class)
            ->ids()
            ->contains($this->id);
    }

    /**
     * Get the model IDs that the given user has permission for.
     *
     * If the user is an admin, the result will be null, meaning there is no
     * restriction on the models they have permission for.
     */
    public static function allPermitted(?User $user, string $ability = 'view'): ?array
    {
        if ($user?->isAdmin()) {
            return null;
        }

        return Waterhole::permissions()
            ->user($user)
            ->ability($ability)
            ->scope(static::class)
            ->ids()
            ->all();
    }
}
