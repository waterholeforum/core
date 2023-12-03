<?php

namespace Waterhole\Auth;

use Laravel\Socialite\Contracts\User;
use Waterhole\Sso\Payload;

class SsoUser implements User
{
    public function __construct(private readonly Payload $payload)
    {
    }

    public function getId()
    {
        return $this->payload->get('externalId');
    }

    public function getNickname()
    {
        return $this->payload->get('username');
    }

    public function getName()
    {
        return $this->payload->get('username');
    }

    public function getEmail()
    {
        return $this->payload->get('email');
    }

    public function getAvatar()
    {
        return $this->payload->get('avatarUrl');
    }

    public function getGroups(): array
    {
        return (array) $this->payload->get('groups', []);
    }
}
