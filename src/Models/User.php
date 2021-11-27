<?php

namespace Waterhole\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Expression;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Image;
use Waterhole\Models\Concerns\HasImageAttributes;
use Waterhole\Models\Concerns\ReceivesPermissions;
use Waterhole\Notifications\ResetPassword;
use Waterhole\Notifications\VerifyEmail;

class User extends Model implements
    AuthenticatableContract,
    MustVerifyEmailContract,
    CanResetPasswordContract,
    HasLocalePreference
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    use HasImageAttributes;
    use ReceivesPermissions;

    const UPDATED_AT = null;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'notification_channels' => AsArrayObject::class,
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'notifications_read_at' => 'datetime',
        'show_online' => 'boolean',
        'follow_on_comment' => 'boolean',
        'suspend_until' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->orderBy('name');
    }

    /**
     * Override the relation from the Notifiable trait to use our own
     * Notification model.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function markNotificationsRead(Model $model): static
    {
        $this->unreadNotifications()
            ->whereMorphedTo('group', $model)
            ->orWhereMorphedTo('content', $model)
            ->update(['read_at' => now()]);

        return $this;
    }

    public function getUnreadNotificationCountAttribute()
    {
        $query = $this->unreadNotifications();

        if ($this->notifications_read_at) {
            $query->where('notifications.created_at', '>', $this->notifications_read_at);
        }

        return $query->distinct()->count([
            'type',
            new Expression('COALESCE(group_type, id)'),
            new Expression('COALESCE(group_id, id)')
        ]);
    }

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    public function getUrlAttribute(): ?string
    {
        return route('waterhole.users.show', ['user' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.users.edit', ['user' => $this]);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->resolvePublicUrl($this->avatar, 'avatars');
    }

    public function removeAvatar()
    {
        $this->removeImage('avatar', 'avatars');
    }

    public function uploadAvatar(Image $image)
    {
        $this->uploadImage($image, 'avatar', 'avatars', function (Image $image) {
            return $image->fit(200)->encode('png');
        });
    }

    public function getCoverUrlAttribute()
    {
        return $this->resolvePublicUrl($this->cover, 'user-covers');
    }

    public function removeCover()
    {
        $this->removeImage('cover', 'user-covers');
    }

    public function uploadCover(Image $image)
    {
        $this->uploadImage($image, 'cover', 'user-covers', function (Image $image) {
            return $image->fit(1000, 300)->encode('jpg');
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this, $this->email));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public static function rules(User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', Password::defaults()],
        ];
    }

    public function isRootAdmin(): bool
    {
        return $this->id === 1;
    }
}
