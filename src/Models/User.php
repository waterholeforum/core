<?php

namespace Waterhole\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Intervention\Image\Image;
use Waterhole\Models\Concerns\HasImageAttributes;
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

    const UPDATED_AT = null;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'notification_channels' => 'json',
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'notifications_read_at' => 'datetime',
        'show_online' => 'boolean',
        'suspend_until' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Override the relation from the Notifiable trait to use our own
     * Notification model.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->orderBy('notifications.created_at', 'desc');
    }

    public function markNotificationsRead(Model $model): static
    {
        $this->notifications()
            ->where(function ($query) use ($model) {
                $query->whereMorphedTo('subject', $model)
                    ->orWhereMorphedTo('content', $model);
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this;
    }

    public function getUnreadNotificationCountAttribute()
    {
        $query = $this->unreadNotifications()->groupBySubject();

        if ($this->notifications_read_at) {
            $query->where('notifications.created_at', '>', $this->notifications_read_at);
        }

        return $query->count();
    }

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    public function getUrlAttribute(): ?string
    {
        return null;
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
            return $image->crop(200, 200)->encode('png');
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
            return $image->crop(1000, 300)->encode('jpg');
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
}
