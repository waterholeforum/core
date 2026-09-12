<?php

declare(strict_types=1);

namespace Waterhole\Auth;

interface AuthenticatesWaterhole
{
    /**
     * Create a Waterhole SSO payload representing this user.
     */
    public function toWaterholePayload(): ?SsoPayload;
}
