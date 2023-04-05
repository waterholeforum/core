<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\ReceivesPermissions;
use Waterhole\Models\Concerns\ValidatesData;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_public
 * @property ?string $color
 * @property string $edit_url
 * @property-read \Illuminate\Database\Eloquent\Collection $users
 */
class Group extends Model
{
    use HasIcon;
    use ReceivesPermissions;
    use ValidatesData;

    public const GUEST_ID = 1;
    public const MEMBER_ID = 2;
    public const ADMIN_ID = 3;

    public $timestamps = false;

    private static array $instances = [];

    /**
     * Relationship with the group's users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Whether this group is the Guest group.
     */
    public function isGuest(): bool
    {
        return $this->getKey() === static::GUEST_ID;
    }

    /**
     * Whether this group is the Member group.
     */
    public function isMember(): bool
    {
        return $this->getKey() === static::MEMBER_ID;
    }

    /**
     * Whether this group is the Admin group.
     */
    public function isAdmin(): bool
    {
        return $this->getKey() === static::ADMIN_ID;
    }

    /**
     * Whether this group is a custom (user-defined) group.
     */
    public function isCustom(): bool
    {
        return !$this->isGuest() && !$this->isMember() && !$this->isAdmin();
    }

    /**
     * Get an instance of the Guest group.
     */
    public static function guest(): static
    {
        return static::$instances[static::GUEST_ID] ??= static::findOrFail(static::GUEST_ID);
    }

    /**
     * Get an instance of the Member group.
     */
    public static function member(): static
    {
        return static::$instances[static::MEMBER_ID] ??= static::findOrFail(static::MEMBER_ID);
    }

    /**
     * Get an instance of the Admin group.
     */
    public static function admin(): static
    {
        return static::$instances[static::ADMIN_ID] ??= static::findOrFail(static::ADMIN_ID);
    }

    /**
     * Get only custom (user-defined) groups.
     */
    public function scopeCustom(Builder $query)
    {
        $query->whereKeyNot([static::GUEST_ID, static::MEMBER_ID, static::ADMIN_ID]);
    }

    /**
     * Get only groups that can be selected for users (admin + custom groups).
     */
    public function scopeSelectable(Builder $query)
    {
        $query->whereKeyNot([static::GUEST_ID, static::MEMBER_ID]);
    }

    public function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.groups.edit', ['group' => $this]),
        )->shouldCache();
    }

    public function usersUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.users.index', [
                'q' =>
                    'group:' .
                    (str_contains($this->name, ' ') ? '"' . $this->name . '"' : $this->name),
            ]),
        )->shouldCache();
    }
}
