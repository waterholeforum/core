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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Expression;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Intervention\Image\Image;
use Waterhole\Auth\AuthenticatesWaterhole;
use Waterhole\Extend\NotificationTypes;
use Waterhole\Models\Concerns\HasImageAttributes;
use Waterhole\Models\Concerns\ReceivesPermissions;
use Waterhole\Models\Concerns\UsesFormatter;
use Waterhole\Notifications\ResetPassword;
use Waterhole\Notifications\VerifyEmail;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property null|\Carbon\Carbon $email_verified_at
 * @property null|string $password
 * @property null|string $remember_token
 * @property null|string $locale
 * @property null|string $headline
 * @property null|string $bio
 * @property null|string $location
 * @property null|string $website
 * @property null|string $avatar
 * @property null|\Carbon\Carbon $created_at
 * @property null|\Carbon\Carbon $last_seen_at
 * @property null|\Carbon\Carbon $suspended_until
 * @property bool $show_online
 * @property null|\Illuminate\Database\Eloquent\Casts\ArrayObject $notification_channels
 * @property null|\Carbon\Carbon $notifications_read_at
 * @property bool $follow_on_comment
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $avatar_url
 * @property-read int $unread_notification_count
 * @property-read \Illuminate\Database\Eloquent\Collection $posts
 * @property-read \Illuminate\Database\Eloquent\Collection $comments
 * @property-read \Illuminate\Database\Eloquent\Collection $groups
 * @property-read \Illuminate\Database\Eloquent\Collection $notifications
 */
class User extends Model implements
    AuthenticatableContract,
    MustVerifyEmailContract,
    CanResetPasswordContract,
    HasLocalePreference
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasImageAttributes;
    use MustVerifyEmail;
    use Notifiable;
    use ReceivesPermissions;
    use UsesFormatter;

    public const UPDATED_AT = null;

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'show_online' => 'boolean',
        'notification_channels' => AsArrayObject::class,
        'notifications_read_at' => 'datetime',
        'follow_on_comment' => 'boolean',
        'suspended_until' => 'datetime',
    ];

    protected ?AuthenticatesWaterhole $originalUser = null;

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->follow_on_comment ??= true;
            $user->notification_channels ??= collect(NotificationTypes::build())->mapWithKeys(
                fn($type) => [$type => ['database', 'mail']],
            );
        });
    }

    /**
     * Relationship with the user's posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship with the user's comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with the user's selected groups.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->orderBy('name');
    }

    /**
     * Relationship with the user's notifications.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Relationship with the user's reactions.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * Relationship with the user's external authentication providers.
     */
    public function authProviders(): HasMany
    {
        return $this->hasMany(AuthProvider::class);
    }

    /**
     * Relationship with the user's file uploads.
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    /**
     * Mark the user's notifications about a specific subject as read.
     */
    public function markNotificationsRead(Model $model): static
    {
        $this->unreadNotifications()
            ->where(
                fn($query) => $query
                    ->whereMorphedTo('group', $model)
                    ->orWhereMorphedTo('content', $model),
            )
            ->update(['read_at' => now()]);

        return $this;
    }

    /**
     * Get the user's preferred locale.
     */
    public function preferredLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Upload a new avatar.
     */
    public function uploadAvatar(Image $image): static
    {
        return $this->uploadImage($image, 'avatar', 'avatars', function (Image $image) {
            return $image->fit(200)->encode('png');
        });
    }

    /**
     * Remove the user's avatar.
     */
    public function removeAvatar(): static
    {
        return $this->removeImage('avatar', 'avatars');
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendEmailVerificationNotification(): void
    {
        NotificationFacade::route('mail', $this->email)->notify(
            new VerifyEmail($this, $this->email),
        );
    }

    /**
     * Only send notification emails to a verified address.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->hasVerifiedEmail() ? $this->email : null;
    }

    /**
     * Send a password reset notification to the user.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Determine whether this user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->groups->contains(Group::ADMIN_ID);
    }

    /**
     * Determine whether this user is the root admin.
     */
    public function isRootAdmin(): bool
    {
        return $this->id === 1;
    }

    /**
     * Determine whether the user is online (active in the last 5 minutes).
     */
    public function isOnline(): bool
    {
        return $this->show_online && $this->last_seen_at?->isAfter(now()->subMinutes(5));
    }

    public function isSuspended(): bool
    {
        return (bool) $this->suspended_until?->isFuture();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.users.show', ['user' => $this]),
        )->shouldCache();
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.users.edit', ['user' => $this]),
        )->shouldCache();
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->resolvePublicUrl($this->avatar, 'avatars'),
        )->shouldCache();
    }

    protected function unreadNotificationCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $query = $this->unreadNotifications();

                if ($this->notifications_read_at) {
                    $query->where('notifications.created_at', '>', $this->notifications_read_at);
                }

                return $query
                    ->distinct()
                    ->count([
                        'type',
                        new Expression('COALESCE(group_type, id)'),
                        new Expression('COALESCE(group_id, id)'),
                    ]);
            },
        )->shouldCache();
    }

    public function broadcastChannelRoute(): string
    {
        return 'Waterhole.Models.User.{user}';
    }

    public function broadcastChannel(): string
    {
        return 'Waterhole.Models.User.' . $this->getKey();
    }

    /**
     * Get the original user that was used to authenticate this Waterhole request.
     */
    public function originalUser(): ?AuthenticatesWaterhole
    {
        return $this->originalUser;
    }

    public function setOriginalUser(AuthenticatesWaterhole $user): static
    {
        $this->originalUser = $user;

        return $this;
    }
}
