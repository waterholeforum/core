<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Licensing\LicenseManager;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpLicensingAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('licensing', function () {
    test('shows valid license status', function () {
        $mock = \Mockery::mock(LicenseManager::class);
        $mock->shouldReceive('valid')->andReturn(true);
        $mock->shouldReceive('invalid')->andReturn(false);
        $mock->shouldReceive('test')->andReturn(false);
        $mock->shouldReceive('public')->andReturn(false);
        $mock->shouldReceive('production')->andReturn(true);
        $mock->shouldReceive('status')->andReturn(200);
        $mock->shouldReceive('error')->andReturn(null);
        app()->instance(LicenseManager::class, $mock);

        $this->actingAs(cpLicensingAdmin())
            ->get(route('waterhole.cp.dashboard'))
            ->assertOk()
            ->assertDontSee('data-key="license"', false);
    });

    test('shows invalid license warning', function () {
        $mock = \Mockery::mock(LicenseManager::class);
        $mock->shouldReceive('valid')->andReturn(false);
        $mock->shouldReceive('invalid')->andReturn(true);
        $mock->shouldReceive('test')->andReturn(false);
        $mock->shouldReceive('public')->andReturn(false);
        $mock->shouldReceive('production')->andReturn(true);
        $mock->shouldReceive('status')->andReturn(200);
        $mock->shouldReceive('error')->andReturn('invalid');
        app()->instance(LicenseManager::class, $mock);

        $this->actingAs(cpLicensingAdmin())
            ->get(route('waterhole.cp.dashboard'))
            ->assertOk()
            ->assertSee('data-key="license"', false);
    });

    test('shows missing license warning', function () {
        $mock = \Mockery::mock(LicenseManager::class);
        $mock->shouldReceive('valid')->andReturn(false);
        $mock->shouldReceive('invalid')->andReturn(true);
        $mock->shouldReceive('test')->andReturn(false);
        $mock->shouldReceive('public')->andReturn(false);
        $mock->shouldReceive('production')->andReturn(true);
        $mock->shouldReceive('status')->andReturn(200);
        $mock->shouldReceive('error')->andReturn('missing');
        app()->instance(LicenseManager::class, $mock);

        $this->actingAs(cpLicensingAdmin())
            ->get(route('waterhole.cp.dashboard'))
            ->assertOk()
            ->assertSee('data-key="license"', false);
    });
});
