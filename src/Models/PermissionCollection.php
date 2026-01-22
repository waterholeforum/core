<?php

namespace Waterhole\Models;

use Closure;
use Illuminate\Database\Eloquent\Collection;

class PermissionCollection extends Collection
{
    private static array $resultCache = [];

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

    public function can(User|Group|null $recipient, string $ability, Model|string $scope): bool
    {
        // Admins are granted every permission.
        if ($recipient instanceof User && $recipient->isAdmin()) {
            return true;
        }

        $recipientType = $recipient?->getMorphClass();
        $recipientId = $recipient?->getKey();

        $scopeType = is_string($scope) ? (new $scope())->getMorphClass() : $scope->getMorphClass();
        $scopeId = is_string($scope) ? null : $scope->getKey();

        $resultKey = "$recipientType|$recipientId|$ability|$scopeType|$scopeId";

        return static::$resultCache[$resultKey] ??= $this->some(
            $this->callback($recipient, $ability, $scope),
        );
    }

    public function ids(User|Group|null $recipient, string $ability, string $scope): array
    {
        return $this->filter($this->callback($recipient, $ability, $scope))
            ->pluck('scope_id')
            ->all();
    }

    private function callback(
        User|Group|null $recipient,
        string $ability,
        Model|string $scope,
    ): Closure {
        $recipients = [];

        $isGuest = !$recipient || ($recipient instanceof Group && $recipient->isGuest());
        if (!$isGuest || config('waterhole.forum.public', true)) {
            $recipients[] = [(new Group())->getMorphClass(), Group::GUEST_ID];
        }

        if (
            $recipient &&
            ($recipient instanceof User || $recipient->getKey() !== Group::GUEST_ID)
        ) {
            $recipients[] = [(new Group())->getMorphClass(), Group::MEMBER_ID];
            $recipients[] = [$recipient->getMorphClass(), $recipient->getKey()];

            if ($recipient instanceof User) {
                $recipients = array_merge(
                    $recipients,
                    $recipient->groups
                        ->map(fn($group) => [$group->getMorphClass(), $group->getKey()])
                        ->all(),
                );
            }
        }

        $scopeType = is_string($scope) ? (new $scope())->getMorphClass() : $scope->getMorphClass();
        $scopeId = is_string($scope) ? null : $scope->getKey();

        // Use loose (==) comparison for IDs below, as in some environments
        // they can be returned as strings instead of ints.
        return function ($item) use ($ability, $recipients, $scopeType, $scopeId) {
            if (
                $item['ability'] !== $ability ||
                $item['scope_type'] !== $scopeType ||
                ($scopeId && $item['scope_id'] != $scopeId)
            ) {
                return false;
            }

            foreach ($recipients as [$recipientType, $recipientId]) {
                if (
                    $item['recipient_type'] === $recipientType &&
                    $item['recipient_id'] == $recipientId
                ) {
                    return true;
                }
            }

            return false;
        };
    }
}
