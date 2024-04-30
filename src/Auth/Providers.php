<?php

namespace Waterhole\Auth;

use Illuminate\Support\Collection;

class Providers
{
    private const DEFAULTS = [
        'facebook' => [
            'icon' => 'tabler-brand-facebook',
            'name' => 'Facebook',
        ],
        'twitter' => [
            'icon' => 'tabler-brand-twitter',
            'name' => 'Twitter',
        ],
        'twitter-oauth-2' => [
            'icon' => 'tabler-brand-twitter',
            'name' => 'Twitter',
        ],
        'linkedin' => [
            'icon' => 'tabler-brand-linkedin',
            'name' => 'LinkedIn',
        ],
        'google' => [
            'icon' => 'waterhole-google',
            'name' => 'Google',
        ],
        'github' => [
            'icon' => 'tabler-brand-github',
            'name' => 'GitHub',
        ],
        'gitlab' => [
            'icon' => 'tabler-brand-gitlab',
            'name' => 'GitLab',
        ],
        'bitbucket' => [
            'icon' => 'tabler-brand-bitbucket',
            'name' => 'Bitbucket',
        ],
    ];

    private Collection $providers;

    public function __construct(protected array $config)
    {
        $this->providers = collect($config)->mapWithKeys(
            fn($value, $key) => is_numeric($key)
                ? [$value => static::DEFAULTS[$value] ?? ['icon' => null, 'name' => $value]]
                : [$key => $value],
        );
    }

    public function all(): array
    {
        return $this->providers->all();
    }

    public function has(string $provider): bool
    {
        return $this->providers->has($provider);
    }

    public function sole(): ?string
    {
        return $this->providers->count() === 1 ? $this->providers->keys()->first() : null;
    }
}
