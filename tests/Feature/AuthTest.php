<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;
use Waterhole\Notifications\ResetPassword;
use Waterhole\Notifications\VerifyEmail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);

    config(['auth.providers.users.model' => User::class]);

    if (!Schema::hasTable('password_reset_tokens')) {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
});

describe('registration', function () {
    test('shows registration form', function () {
        $this->get(route('waterhole.register'))->assertOk();
    });

    test('registers a user with valid input', function () {
        $this->post(route('waterhole.register.submit'), [
            'name' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('waterhole.home'));

        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    });

    test('rejects invalid registration input', function () {
        $this->from(route('waterhole.register'))
            ->post(route('waterhole.register.submit'), [
                'name' => '',
                'email' => 'not-email',
                'password' => 'short',
            ])
            ->assertRedirect(route('waterhole.register'))
            ->assertSessionHasErrors(['name', 'email', 'password']);
    });
});

describe('login', function () {
    test('shows login form', function () {
        $this->get(route('waterhole.login'))->assertOk();
    });

    test('authenticates with valid credentials', function () {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);

        $this->post(URL::route('waterhole.login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('waterhole.home'));

        $this->assertAuthenticatedAs($user);
    });

    test('rejects invalid credentials', function () {
        $user = User::factory()->create(['password' => bcrypt('Password123!')]);

        $this->from(route('waterhole.login'))
            ->post(URL::route('waterhole.login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('waterhole.login'));

        $this->assertGuest();
    });
});

describe('password reset', function () {
    test('shows forgot password form', function () {
        $this->get(route('waterhole.forgot-password'))->assertOk();
    });

    test('sends reset link for valid email', function () {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->post(URL::route('waterhole.forgot-password'), [
            'email' => $user->email,
        ])->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('shows reset form with token', function () {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->get(route('waterhole.reset-password', ['token' => $token]))->assertOk();
    });

    test('resets password with valid token', function () {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->post(URL::route('waterhole.reset-password', ['token' => $token]), [
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('waterhole.login'));
    });

    test('rejects invalid token', function () {
        $user = User::factory()->create();

        $this->from(URL::route('waterhole.reset-password', ['token' => 'invalid-token']))
            ->post(URL::route('waterhole.reset-password', ['token' => 'invalid-token']), [
                'email' => $user->email,
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertSessionHasErrors(['token']);
    });
});

describe('email verification', function () {
    test('shows verification notice for unverified users', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get(route('waterhole.home'))
            ->assertOk()
            ->assertSee('verify', false);
    });

    test('verifies email with valid signed link', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $url = URL::signedRoute('waterhole.verify-email', [
            'id' => $user->id,
            'email' => $user->email,
        ]);

        $this->actingAs($user)->get($url)->assertRedirect(route('waterhole.home'));

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    });

    test('rejects invalid/expired verification links', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get(route('waterhole.verify-email', ['id' => $user->id]))
            ->assertForbidden();
    });

    test('requires re-verification after configured days', function () {
        config(['waterhole.users.reverify_after_inactive_days' => 30]);
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => now()->subDays(100),
            'last_seen_at' => now()->subDays(60),
            'password' => bcrypt('Password123!'),
        ]);

        $this->post(URL::route('waterhole.login'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('waterhole.home'));

        Notification::assertSentOnDemand(VerifyEmail::class);
    });
});

describe('sso', function () {
    test('redirects to sso provider with signed payload', function () {
        $this->get(route('waterhole.sso.login', ['provider' => 'invalid']))->assertStatus(400);
    });

    test('accepts valid sso payload and logs in', function () {
        config(['waterhole.auth.providers' => ['github']]);

        $existingUser = User::factory()->create(['email' => 'sso@example.com']);

        $socialUser = new class {
            public function getId()
            {
                return 'provider-user-1';
            }
            public function getEmail()
            {
                return 'sso@example.com';
            }
            public function getNickname()
            {
                return 'sso-user';
            }
            public function getName()
            {
                return 'SSO User';
            }
            public function getAvatar()
            {
                return null;
            }
        };

        $driver = \Mockery::mock();
        $driver->shouldReceive('user')->andReturn($socialUser);

        Socialite::shouldReceive('driver')->once()->with('github')->andReturn($driver);

        $this->get(route('waterhole.sso.callback', ['provider' => 'github']))->assertRedirect(
            route('waterhole.home'),
        );

        $this->assertAuthenticatedAs($existingUser);
    });

    test('accepts valid sso payload and continues registration when user missing', function () {
        config(['waterhole.auth.providers' => ['github']]);

        $socialUser = new class {
            public function getId()
            {
                return 'provider-user-2';
            }
            public function getEmail()
            {
                return 'new-sso@example.com';
            }
            public function getNickname()
            {
                return null;
            }
            public function getName()
            {
                return 'New SSO User';
            }
            public function getAvatar()
            {
                return null;
            }
        };

        $driver = \Mockery::mock();
        $driver->shouldReceive('user')->andReturn($socialUser);

        Socialite::shouldReceive('driver')->once()->with('github')->andReturn($driver);

        $this->get(
            route('waterhole.sso.callback', ['provider' => 'github']),
        )->assertRedirectContains('/register/');

        $this->assertGuest();
    });

    test('rejects invalid sso payload', function () {
        $this->get(route('waterhole.register.payload', ['payload' => 'invalid']))->assertStatus(
            400,
        );
    });
});
