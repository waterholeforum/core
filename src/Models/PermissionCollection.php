<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Collection;

class PermissionCollection extends Collection
{
    public function group($id): static
    {
        if ($id instanceof Group) {
            $id = $id->id;
        }

        $ids = [$id];

        if ($id !== Group::GUEST_ID) {
            $ids[] = Group::GUEST_ID;

            if ($id !== Group::MEMBER_ID) {
                $ids[] = Group::MEMBER_ID;
            }
        }

        return $this
            ->where('recipient_type', (new Group())->getMorphClass())
            ->whereIn('recipient_id', $ids);
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

    public function can(string $ability): bool
    {
        return $this->ability($ability)->isNotEmpty();
    }
}
