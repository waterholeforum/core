<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class PermissionCollection extends Collection
{
    /**
     * Get the permission records received by a specific user.
     */
    public function user(?User $user): static
    {
        if (!$user) {
            return $this->guest();
        }

        return $this->group($user->groups)->merge(
            $this->where('recipient_type', $user->getMorphClass())->where(
                'recipient_id',
                $user->getKey(),
            ),
        );
    }

    /**
     * Get the permission records received by any group.
     */
    public function groups(): static
    {
        return $this->where('recipient_type', (new Group())->getMorphClass());
    }

    /**
     * Get the permission records received by any of the specified groups.
     */
    public function group(Group|int|array|Collection $group): static
    {
        $ids = collect($group instanceof Group ? [$group] : $group)->map(
            fn($group) => $group instanceof Group ? $group->id : $group,
        );

        if (!$ids->contains(Group::GUEST_ID)) {
            $ids->push(Group::GUEST_ID);

            if (!$ids->contains(Group::MEMBER_ID)) {
                $ids->push(Group::MEMBER_ID);
            }
        }

        return $this->groups()->whereIn('recipient_id', $ids);
    }

    /**
     * Get the permission records received by guests.
     */
    public function guest(): static
    {
        return $this->group(Group::GUEST_ID);
    }

    /**
     * Get the permission records received by members.
     */
    public function member(): static
    {
        return $this->group(Group::MEMBER_ID);
    }

    /**
     * Get the permission records pertaining to a specific model.
     */
    public function scope(Model|string $model): static
    {
        if (is_string($model)) {
            if (class_exists($model)) {
                $model = (new $model())->getMorphClass();
            }

            return $this->where('scope_type', $model);
        }

        return $this->where('scope_type', $model->getMorphClass())->where(
            'scope_id',
            $model->getKey(),
        );
    }

    /**
     * Get the scope IDs present in the permission collection.
     */
    public function ids(): BaseCollection
    {
        return $this->pluck('scope_id');
    }

    /**
     * Get the permission records pertaining to a specific ability.
     */
    public function ability(string $ability): static
    {
        return $this->where('ability', $ability);
    }

    /**
     * Determine whether this set of permissions contains a specific ability.
     */
    public function allows(string $ability, Model $model = null): bool
    {
        return ($model ? $this->scope($model) : $this)->ability($ability)->isNotEmpty();
    }

    /**
     * Determine whether a user has an ability in this set of permissions.
     *
     * For Admins, this will always return true.
     */
    public function can(?User $user, string $ability, Model $model = null): bool
    {
        if ($user?->groups->contains(Group::ADMIN_ID)) {
            return true;
        }

        return $this->user($user)->allows($ability, $model);
    }
}
