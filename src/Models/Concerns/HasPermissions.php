<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Permission;

trait HasPermissions
{
    public static function bootHasPermissions()
    {
        static::deleted(function (self $model) {
            $model->permissions()->delete();
        });
    }

    public function permissions(): MorphMany
    {
        return $this->morphMany(Permission::class, 'scope');
    }

    public function abilities(): array
    {
        return ['view'];
    }

    public function savePermissions(?array $grid): void
    {
        $this->permissions()->delete();

        if (! $grid) {
            return;
        }

        $this->permissions()->createMany(
            collect($grid)->flatMap(function ($abilities, $recipient) {
                [$type, $id] = explode(':', $recipient) + [null, null];

                if (! in_array($type, ['group', 'user'])) {
                    return [];
                }

                return collect($abilities)->filter()->map(fn($v, $ability) => [
                    'recipient_type' => $type,
                    'recipient_id' => $id,
                    'ability' => $ability,
                ])->values();
            })
        );
    }
}
