<?php

namespace Waterhole\Auth;

use Illuminate\Contracts\Encryption\DecryptException;
use Waterhole\Sso\PendingUser;

class SsoPayload
{
    private int $expiry;

    public function __construct(public string $provider, public PendingUser $user)
    {
        $this->expiry = time() + 10 * 60;
    }

    public static function decrypt(string $value): static
    {
        try {
            /** @var static $payload */
            $payload = decrypt($value);
        } catch (DecryptException) {
            abort(400, 'Invalid payload');
        }

        if (time() > $payload->expiry) {
            abort(400, 'Payload expired');
        }

        return $payload;
    }

    public function encrypt(): string
    {
        return encrypt($this);
    }

    public function __toString(): string
    {
        return $this->encrypt();
    }
}
