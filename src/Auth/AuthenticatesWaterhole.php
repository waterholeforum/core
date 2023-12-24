<?php

namespace Waterhole\Auth;

interface AuthenticatesWaterhole
{
    /**
     * Create a Waterhole SSO payload representing this user.
     */
    public function toWaterholePayload(): ?SsoPayload;
}
