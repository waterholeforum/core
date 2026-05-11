<?php

namespace Waterhole\Auth;

use Illuminate\Database\Eloquent\Model;

class GateCache
{
    private array $results = [];

    public function remembered(mixed $user, mixed $ability, mixed $arguments): mixed
    {
        if (!is_string($ability) || !str_starts_with($ability, 'waterhole.')) {
            return null;
        }

        $key = $this->key($user, $ability, $arguments);

        return $this->results[$key] ?? null;
    }

    public function store(mixed $user, mixed $ability, mixed $arguments, mixed $result): void
    {
        if (!is_string($ability) || !str_starts_with($ability, 'waterhole.') || $result === null) {
            return;
        }

        $this->results[$this->key($user, $ability, $arguments)] = $result;
    }

    private function key(mixed $user, string $ability, mixed $arguments): string
    {
        return implode('|', [
            $this->userKey($user),
            $ability,
            serialize($this->argumentKeys($arguments)),
        ]);
    }

    private function userKey(mixed $user): string
    {
        if (!$user) {
            return 'guest';
        }

        if ($user instanceof Model) {
            return $user->exists ? $this->persistedModelKey($user) : $user::class . ':new';
        }

        return get_debug_type($user);
    }

    private function argumentKeys(mixed $arguments): array
    {
        return array_map($this->argumentKey(...), is_array($arguments) ? $arguments : [$arguments]);
    }

    private function argumentKey(mixed $argument): mixed
    {
        if ($argument instanceof Model) {
            return $argument->exists
                ? $this->persistedModelKey($argument)
                : $argument::class . ':new:' . spl_object_id($argument);
        }

        if (is_array($argument)) {
            return $this->argumentKeys($argument);
        }

        return is_object($argument) ? spl_object_id($argument) : $argument;
    }

    private function persistedModelKey(Model $model): string
    {
        return $model::class . ':' . $model->getKey();
    }
}
