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
use Waterhole\Models\Concerns\ValidatesData;
use Waterhole\Notifications\ResetPassword;
use Waterhole\Notifications\VerifyEmail;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property ?\Carbon\Carbon $email_verified_at
 * @property ?string $password
 * @property ?string $remember_token
 * @property ?string $locale
 * @property ?string $headline
 * @property ?string $bio
 * @property ?string $location
 * @property ?string $website
 * @property ?string $avatar
 * @property ?\Carbon\Carbon $created_at
 * @property ?\Carbon\Carbon $last_seen_at
 * @property bool $show_online
 * @property ?\Illuminate\Database\Eloquent\Casts\ArrayObject $notification_channels
 * @property ?\Carbon\Carbon $notifications_read_at
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
    use ValidatesData;

    public const UPDATED_AT = null;

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'show_online' => 'boolean',
        'notification_channels' => AsArrayObject::class,
        'notifications_read_at' => 'datetime',
        'follow_on_comment' => 'boolean',
    ];

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
     * Mark the user's notifications about a specific subject as read.
     */
    public function markNotificationsRead(Model $model): static
    {
        $this->unreadNotifications()
            ->whereMorphedTo('group', $model)
            ->orWhereMorphedTo('content', $model)
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
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this, $this->email));
    }

    /**
     * Send a password reset notification to the user.
     */
    public function sendPasswordResetNotification($token)
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

    public function getUrlAttribute(): string
    {
        return route('waterhole.users.show', ['user' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.users.edit', ['user' => $this]);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->resolvePublicUrl($this->avatar, 'avatars');
    }

    public function getUnreadNotificationCountAttribute(): int
    {
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
    }

    public static function rules(User $instance = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($instance)],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($instance),
            ],
            'password' => [$instance ? 'nullable' : 'required', Password::defaults()],
        ];
    }
}
