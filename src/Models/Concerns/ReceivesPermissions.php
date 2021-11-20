<?php

namespace Waterhole\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Waterhole\Models\Permission;

trait ReceivesPermissions
{
    public static function bootReceivesPermissions()
    {
        static::deleted(function (self $model) {
            $model->permissions()->delete();
        });
    }

    public function permissions(): MorphMany
    {
        return $this->morphMany(Permission::class, 'recipient');
    }

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
