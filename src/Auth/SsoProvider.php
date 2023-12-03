<?php

namespace Waterhole\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Waterhole\Sso\Payload;
use Waterhole\Sso\WaterholeSso;

final class SsoProvider implements Provider
{
    private ?SsoUser $user = null;

    public function __construct(
        private readonly Request $request,
        private readonly string $url,
        private readonly WaterholeSso $sso,
    ) {
    }

    public function redirect(): RedirectResponse
    {
        $this->storeNonce($nonce = $this->generateNonce());

        $query = $this->sso->buildQuery($nonce, [
            Payload::RETURN_URL => route('waterhole.sso.callback', ['provider' => 'sso']),
        ]);

        return redirect($this->url . (str_contains($this->url, '?') ? '&' : '?') . $query);
    }

    public function user(): SsoUser
    {
        if ($this->user) {
            return $this->user;
        }

        $payload = $this->request->query('payload');
        $sig = $this->request->query('sig');

        if (!$this->sso->validate($payload, $sig)) {
            abort(400, 'Invalid signature');
        }

        $payload = $this->sso->parse($payload);

        if (!$this->validateNonce($payload->getNonce())) {
            abort(400, 'Invalid nonce');
        }

        if (!$this->validatePayload($payload)) {
            abort(400, 'Invalid payload');
        }

        return $this->user = new SsoUser($payload);
    }

    private function generateNonce(): string
    {
        return Str::random();
    }

    private function storeNonce(string $nonce): void
    {
        $this->request->session()->put('sso_nonce', [
            'nonce' => $nonce,
            'expiry' => time() + 10 * 60,
        ]);
    }

    private function validateNonce(string $nonce): bool
    {
        $data = $this->request->session()->get('sso_nonce');

        $storedNonce = $data['nonce'] ?? null;
        $expiry = $data['expiry'] ?? null;

        return $storedNonce === $nonce && time() < $expiry;
    }

    private function validatePayload(Payload $payload): bool
    {
        return $payload->get('externalId') && $payload->get('email') && $payload->get('username');
    }
}
