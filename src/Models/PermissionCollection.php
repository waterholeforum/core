<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Collection;

class PermissionCollection extends Collection
{

    public function user(?User $user): static
    {
        if (! $user) {
            return $this->guest();
        }

        return $this
            ->group($user->groups)
            ->merge($this
                ->where('recipient_type', $user->getMorphClass())
                ->where('recipient_id', $user->getKey()));
    }

    public function groups(): static
    {
        return $this->where('recipient_type', (new Group())->getMorphClass());
    }

    public function group($group): static
    {
        $ids = collect($group instanceof Group ? [$group] : $group)
            ->map(fn($group) => $group instanceof Group ? $group->id : $group);

        if (! $ids->contains(Group::GUEST_ID)) {
            $ids->push(Group::GUEST_ID);

            if (! $ids->contains(Group::MEMBER_ID)) {
                $ids->push(Group::MEMBER_ID);
            }
        }

        return $this->groups()->whereIn('recipient_id', $ids);
    }

    public function guest(): static
    {
        return $this->group(Group::GUEST_ID);
    }

    public function member(): static
    {
        return $this->group(Group::MEMBER_ID);
    }

    public function scope($model): static
    {
        return $this
            ->where('scope_type', $model->getMorphClass())
            ->where('scope_id', $model->getKey());
    }

    public function ability(string $ability): static
    {
        return $this->where('ability', $ability);
    }

    public function allows(string $ability): bool
    {
        return $this->ability($ability)->isNotEmpty();
    }

    public function can(?User $user, string $ability): bool
    {
        if (! $user) {
            return $this->guest()->allows($ability);
        }

        if ($user->groups->contains(Group::ADMIN_ID)) {
            return true;
        }

        return $this->user($user)->allows($ability);
    }
}
