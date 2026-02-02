<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;

uses(RefreshDatabase::class)->group('browser');

beforeEach(function () {
    $this->seed(GroupsSeeder::class);

    config(['filesystems.disks.public.url' => '/storage']);
});

describe('auth', function () {
    test('registers and lands on the forum home', function () {
        $email = 'browser-register@example.com';

        visit(route('waterhole.register'))
            ->fill('name', 'browser-user')
            ->fill('email', $email)
            ->fill('password', 'Password123!')
            ->click('button[type="submit"]')
            ->assertSee('browser-user');

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertAuthenticated();
    });

    test('debug assets', function () {
        visit(route('waterhole.home'))->dd();
    })->only();

    test('logs in and logs out', function () {
        $user = User::factory()->create([
            'email' => 'browser-login@example.com',
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
        ]);

        visit(route('waterhole.login'))
            ->fill('email', $user->email)
            ->fill('password', 'Password123!')
            ->click('button[type="submit"]');

        expect(auth()->id())->toBe($user->id);

        visit(route('waterhole.home'))
            ->click('.header-user > a')
            ->click('form[action*="logout"] button.menu-item');

        $this->assertGuest();
    });
});
