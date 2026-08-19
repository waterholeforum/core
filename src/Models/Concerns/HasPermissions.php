<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\Permission;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 */
trait HasPermissions
{
    public static function bootHasPermissions(): void
    {
        static::addGlobalScope('visible', new PermittedScope());

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

    public function permissionScope(string $ability): Model
    {
        return $this;
    }

    /**
     * Save the permissions to the database.
     */
    public function savePermissions(?array $grid): void
    {
        $scopeKey = fn(Model $scope) => $scope->getMorphClass() . ':' . $scope->getKey();

        collect($this->abilities())
            ->map(fn(string $ability) => $this->permissionScope($ability))
            ->unique($scopeKey)
            ->each(fn(Model $scope) => $scope->permissions()->delete());

        if (!$grid) {
            return;
        }

        $permissions = [];

        foreach ($grid as $recipient => $abilities) {
            [$type, $id] = explode(':', $recipient) + [null, null];

            foreach (array_keys(array_filter($abilities)) as $ability) {
                $scope = $this->permissionScope($ability);
                $key = $scopeKey($scope);
                $permissions[$key]['scope'] = $scope;
                $permissions[$key]['attributes'][] = [
                    'recipient_type' => $type,
                    'recipient_id' => $id,
                    'ability' => $ability,
                ];
            }
        }

        foreach ($permissions as $group) {
            $group['scope']->permissions()->createMany($group['attributes']);
        }
    }

    public function isPublic(string $ability = 'view'): bool
    {
        return Waterhole::permissions()->can(null, $ability, $this);
    }

    public function usersWithAbility(string $ability): ?Collection
    {
        if (
            $this->isPublic($ability)
            || Waterhole::permissions()->can(Group::member(), $ability, $this)
        ) {
            return null;
        }

        $permissions = $this->permissionScope($ability)->permissions()->where('ability', $ability);

        $groupIds = $permissions
            ->where('recipient_type', (new Group())->getMorphClass())
            ->pluck('recipient_id');

        $userIds = $permissions
            ->where('recipient_type', (new User())->getMorphClass())
            ->pluck('recipient_id');

        $groupUserIds = DB::table('group_user')
            ->whereIn('group_id', [...$groupIds, Group::ADMIN_ID])
            ->pluck('user_id');

        $userIds = $groupUserIds->merge($userIds)->unique()->values();

        return User::with('groups')
            ->findMany($userIds)
            ->filter(fn(User $user) => Waterhole::permissions()->can($user, $ability, $this))
            ->values();
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

        return Waterhole::permissions()->ids($user, $ability, static::class);
    }
}
