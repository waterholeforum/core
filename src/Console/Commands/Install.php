<?php

namespace Waterhole\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Waterhole\Console\Commands\Concerns\ValidatesInput;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Models\Group;
use Waterhole\Models\User;

class Install extends Command
{
    use ValidatesInput;

    protected $signature = 'waterhole:install';

    protected $description = 'Install Waterhole';

    public function handle()
    {
        $this->publish();
        $this->migrate();
        $this->seed();
        $this->createAdmin();
    }

    private function publish(): void
    {
        $this->call('vendor:publish', ['--tag' => 'waterhole']);
    }

    private function migrate(): void
    {
        $this->call('migrate');
    }

    private function seed(): void
    {
        $this->call('db:seed', [
            '--class' => DefaultSeeder::class,
            '--force' => true,
        ]);
    }

    private function createAdmin(): void
    {
        $rules = User::rules();

        $data = [
            'name' => $this->askValid('Admin username', 'name', $rules['name']),
            'email' => $this->askValid('Admin email', 'email', $rules['email']),
            'password' => Hash::make($this->askValid('Admin password', 'password', $rules['password'], secret: true)),
            'email_verified_at' => now(),
        ];

        User::create($data)->groups()->attach(Group::ADMIN_ID);

        $this->info('Admin user created.');
    }
}
