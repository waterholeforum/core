<?php

namespace Waterhole\Licensing;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Waterhole\Waterhole;

class Outpost
{
    const ENDPOINT = 'https://api.waterhole.dev/v1/outpost';
    const TIMEOUT = 5;
    const CACHE_KEY = 'waterhole.outpost';

    private array $response;

    public function __construct(private Repository $cache) {}

    public function contact(): array
    {
        return $this->response ??= $this->request();
    }

    private function request(): array
    {
        $cached = $this->cache->get(static::CACHE_KEY);
        $payload = $this->payload();

        if ($cached && !$this->payloadHasChanged($cached['payload'], $payload)) {
            return $cached['response'];
        }

        try {
            $response = Http::throw()
                ->timeout(static::TIMEOUT)
                ->connectTimeout(static::TIMEOUT)
                ->post(static::ENDPOINT, $payload);

            $json = $response->json();
            $json['status'] = $response->status();
            $expiry = now()->addHour();
        } catch (RequestException $e) {
            $json = ['status' => $e->response->status()];

            if ($json['status'] === 422) {
                $json['message'] = $e->response->json('message');
            }

            $expiry = match ($json['status']) {
                429 => now()->addSeconds($e->response->header('Retry-After')[0]),
                default => now()->addMinutes(5),
            };

            report($e);
        } catch (ConnectionException $e) {
            $json = ['status' => 0];
            $expiry = now()->addMinutes(5);
            report($e);
        }

        $this->cache->put(static::CACHE_KEY, ['payload' => $payload, 'response' => $json], $expiry);

        return $json;
    }

    private function payload(): array
    {
        return [
            'key' => config('waterhole.system.site_key'),
            'host' => request()->getHost(),
            'ip' => request()->server('SERVER_ADDR'),
            'port' => request()->server('SERVER_PORT'),
            'waterhole_version' => Waterhole::VERSION,
            'php_version' => PHP_VERSION,
            'packages' => [],
        ];
    }

    private function payloadHasChanged($previous, $current): bool
    {
        $exclude = ['ip'];

        return Arr::except($previous, $exclude) !== Arr::except($current, $exclude);
    }
}
