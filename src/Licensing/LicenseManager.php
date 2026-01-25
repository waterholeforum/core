<?php

namespace Waterhole\Licensing;

class LicenseManager
{
    public function __construct(private Outpost $outpost) {}

    public function response(?string $key = null, $default = null)
    {
        $response = $this->outpost->contact();

        return data_get($response, $key, $default);
    }

    public function status(): int
    {
        return $this->response('status');
    }

    public function error(): ?string
    {
        return $this->response('waterhole.error');
    }

    public function public(): bool
    {
        return (bool) $this->response('public');
    }

    public function test(): bool
    {
        return $this->status() === 200 && !$this->public();
    }

    public function production(): bool
    {
        return !$this->test();
    }

    public function valid(): bool
    {
        return (bool) $this->response('waterhole.valid');
    }

    public function invalid(): bool
    {
        return !$this->valid();
    }
}
