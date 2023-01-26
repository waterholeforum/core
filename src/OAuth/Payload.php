<?php

namespace Waterhole\OAuth;

class Payload
{
    public function __construct(
        public string $provider,
        public string $identifier,
        public string $email,
        public ?string $name = null,
        public ?string $avatar = null,
    ) {
    }

    public static function decrypt(?string $value): ?static
    {
        return $value ? decrypt($value) : null;
    }

    public function encrypt(): string
    {
        return encrypt($this);
    }
}
