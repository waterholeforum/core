<?php

namespace Waterhole\Licensing;

class LicenseManager
{
    public function __construct(private Outpost $outpost)
    {
    }

    public function response(string $key = null, $default = null)
    {
        $response = $this->outpost->contact();

        return data_get($response, $key, $default);
    }

    public function error(): ?string
    {
        return $this->response('error');
    }

    public function public(): bool
    {
        return $this->response('public');
    }

    public function test(): bool
    {
        return !$this->public();
    }

    public function valid(): bool
    {
        return $this->response('waterhole.valid');
    }

    public function invalid(): bool
    {
        return !$this->valid();
    }
}
