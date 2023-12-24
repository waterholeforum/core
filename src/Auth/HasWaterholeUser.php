<?php

namespace Waterhole\Auth;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Waterhole\Models\AuthProvider;
use Waterhole\Models\User;
use Waterhole\Sso\PendingUser;

trait HasWaterholeUser
{
    public static function bootHasWaterholeUser(): void
    {
        static::saved(function ($model) {
            if ($model->wasChanged('email', 'email_verified_at')) {
                $model->waterholeUser()->update([
                    'email' => $model->email,
                    'email_verified_at' => $model->email_verified_at,
                ]);
            }
        });
    }

    public function waterholeUser(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            AuthProvider::class,
            firstKey: 'identifier',
            secondKey: 'id',
            localKey: $this->getAuthIdentifierName(),
            secondLocalKey: 'user_id',
        )->where('provider', $this->getWaterholeProviderName());
    }

    public function toWaterholeUser(): ?PendingUser
    {
        return new PendingUser(
            identifier: $this->getAuthIdentifier(),
            email: $this->email,
            name: $this->name,
        );
    }

    public function toWaterholePayload(): ?SsoPayload
    {
        if ($user = $this->toWaterholeUser()) {
            return new SsoPayload($this->getWaterholeProviderName(), $user);
        }

        return null;
    }

    protected function getWaterholeProviderName(): string
    {
        return 'laravel';
    }
}
