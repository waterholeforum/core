<?php

declare(strict_types=1);

use Waterhole\Models\User;

test('component lists allow conditional entries to return null', function () {
    $this->blade('@components($components)', [
        'components' => [fn() => null, new Illuminate\Support\HtmlString('Visible Component')],
    ])->assertSee('Visible Component');
});

test('alerts render without a type', function () {
    $this
        ->blade('<x-waterhole::alert message="Untyped Alert" />')
        ->assertSee('Untyped Alert')
        ->assertDontSee('alert__icon');
});

test('avatars render a placeholder for users without a name', function () {
    $this
        ->blade('<x-waterhole::avatar :user="$user" />', ['user' => new User()])
        ->assertSee('?')
        ->assertSee('hsl(0 50% 50% / 0.5)');
});
