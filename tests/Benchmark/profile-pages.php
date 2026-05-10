<?php

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Livewire\Blaze\DebuggerMiddleware;
use Orchestra\Testbench\Foundation\Application as Testbench;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

require __DIR__ . '/../../vendor/autoload.php';

try {
    profile('Forum index', function (): array {
        $channel = Channel::factory()->public()->create();
        $viewer = User::factory()->create();
        $users = User::factory(8)->create();

        for ($i = 0; $i < 40; $i++) {
            Post::factory()
                ->for($channel)
                ->for($users->random())
                ->create();
        }

        return [route('waterhole.home'), $viewer->id];
    });

    profile('Post page', function (): array {
        $channel = Channel::factory()->public()->create();
        $viewer = User::factory()->create();
        $users = User::factory(8)->create();
        $post = Post::factory()
            ->for($channel)
            ->for($users->first())
            ->create();

        for ($i = 0; $i < 30; $i++) {
            Comment::factory()
                ->for($post)
                ->for($users->random())
                ->create();
        }

        return [route('waterhole.posts.show', $post), $viewer->id];
    });
} catch (Throwable $e) {
    fwrite(STDERR, $e::class . ': ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, $e->getFile() . ':' . $e->getLine() . PHP_EOL);
    fwrite(STDERR, $e->getTraceAsString() . PHP_EOL);
    exit(1);
}

function profile(string $name, Closure $seed): void
{
    $app = bootWaterhole();

    try {
        [$url, $userId] = $seed();

        app('blaze.debugger')->store->clear();

        $queries = [];
        DB::listen(function ($query) use (&$queries) {
            $queries[] = $query->time;
        });

        requestPage($app, $url, $userId);

        $trace = app('blaze.debugger')->store->getLatestTrace();
        if ($trace === null) {
            throw new RuntimeException('No Blaze trace was written.');
        }

        $traceId = app('blaze.debugger')->store->listTraces(1)[0]['id'] ?? 'unknown';
        $components = collect($trace['components'])
            ->sortByDesc('selfTime')
            ->take(20)
            ->values();

        printf("\n%s [%s]\n", $name, $url);
        printf("Trace: %s\n", $traceId);
        printf(
            "Queries: %d (%.2fms)\n",
            count($queries),
            array_sum($queries),
        );
        printf("%-4s %-42s %7s %11s %11s\n", '#', 'Component', 'Count', 'Self', 'Total');

        foreach ($components as $index => $component) {
            printf(
                "%-4d %-42s %7d %10.2fms %10.2fms\n",
                $index + 1,
                $component['name'],
                $component['count'],
                $component['selfTime'],
                $component['totalTime'],
            );
        }
    } finally {
        $app->terminate();
        $app->flush();
        Facade::clearResolvedInstances();
    }
}

function bootWaterhole(): Application
{
    $app = Testbench::create(
        options: [
            'enables_package_discoveries' => true,
            'extra' => [
                'env' => [
                    'APP_ENV=testing',
                    'APP_DEBUG=false',
                    'APP_KEY=AckfSECXIvnK5r28GVIWUAxmbBSjTsmF',
                    'BLAZE_DEBUG=true',
                    'CACHE_STORE=array',
                    'DB_CONNECTION=testing',
                    'DB_DATABASE=:memory:',
                    'MAIL_MAILER=array',
                    'QUEUE_CONNECTION=sync',
                    'SESSION_DRIVER=array',
                ],
            ],
        ],
    );

    $app['config']->set('auth.providers.users.model', User::class);
    $app->make(ConsoleKernel::class)->bootstrap();

    app('blaze')->disable();
    app('blaze')->debug();
    DebuggerMiddleware::register();

    Artisan::call('view:clear');
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed', ['--class' => GroupsSeeder::class]);

    return $app;
}

function requestPage(Application $app, string $url, int $userId): void
{
    Auth::guard()->login(User::findOrFail($userId));

    $request = Request::create($url);
    $response = $app->make(HttpKernel::class)->handle($request);

    if ($response->getStatusCode() !== 200) {
        throw new RuntimeException(
            "Expected [200] status code but received [{$response->getStatusCode()}]: " .
                substr(strip_tags($response->getContent()), 0, 500),
        );
    }

    $response->getContent();
    $app->make(HttpKernel::class)->terminate($request, $response);
}
