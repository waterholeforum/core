<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $identifier
 * @property \Carbon\Carbon $created_at
 * @property null|\Carbon\Carbon $last_login_at
 * @property-read User $user
 */
class AuthProvider extends Model
{
    const UPDATED_AT = null;

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
