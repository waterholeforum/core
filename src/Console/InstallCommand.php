<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Waterhole\Console\Concerns\ValidatesInput;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Models\Group;
use Waterhole\Models\User;

class InstallCommand extends Command
{
    use ValidatesInput;

    protected $signature = 'waterhole:install';

    protected $description = 'Install Waterhole';

    public function handle()
    {
        if (User::find(1)) {
            $this->error('Waterhole has already been installed.');
            return;
        }

        $this->publish();
        $this->migrate();
        $this->seed();
        $this->createAdmin();

        $this->info('Waterhole successfully installed.');
    }

    private function publish(): void
    {
        $this->call('vendor:publish', ['--tag' => 'waterhole-config']);
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
        $data = [
            'name' => $this->askValid('Admin username', 'name', ['required', 'string', 'max:255']),
            'email' => $this->askValid('Admin email', 'email', [
                'required',
                'string',
                'email',
                'max:255',
            ]),
            'password' => Hash::make(
                $this->askValid(
                    'Admin password',
                    'password',
                    ['required', Password::defaults()],
                    secret: true,
                ),
            ),
            'email_verified_at' => now(),
        ];

        User::create($data)
            ->groups()
            ->attach(Group::ADMIN_ID);

        $this->info('Admin user created.');
    }
}
