<?php

namespace Waterhole\Licensing;

use Exception;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Waterhole\Waterhole;

class Outpost
{
    const ENDPOINT = 'https://api.waterhole.dev/v1/outpost';
    const TIMEOUT = 5;
    const CACHE_KEY = 'waterhole.outpost';

    private array $response;

    public function __construct(private Repository $cache)
    {
    }

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
            $response = Http::timeout(static::TIMEOUT)
                ->connectTimeout(static::TIMEOUT)
                ->post(static::ENDPOINT, $payload);

            $expiry = now()->addHour();

            switch ($response->status()) {
                case 200:
                    $json = $response->json();
                    break;

                case 422:
                    $json = ['error' => 422, 'message' => $response->json('message')];
                    break;

                case 429:
                    $json = ['error' => 429];
                    $expiry = now()->addSeconds($response->header('Retry-After')[0]);
                    break;

                default:
                    $json = ['error' => $response->status()];
                    $expiry = now()->addMinutes(5);
            }
        } catch (Exception) {
            $json = ['error' => 500];
            $expiry = now()->addMinutes(5);
        } finally {
            $this->cache->put(
                static::CACHE_KEY,
                ['payload' => $payload, 'response' => $json],
                $expiry,
            );

            return $json;
        }
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
