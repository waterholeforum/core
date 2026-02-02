<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Waterhole\Models\User;

abstract class TestCase extends BaseTestCase
{
    protected $enablesPackageDiscoveries = true;

    protected function defineEnvironment($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
