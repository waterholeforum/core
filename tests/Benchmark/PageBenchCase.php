<?php

namespace Benchmarks;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testbench\Foundation\Application as Testbench;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Bookmark;
use Waterhole\Models\Channel;
use Waterhole\Models\ChannelUser;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\PostUser;
use Waterhole\Models\User;

abstract class PageBenchCase
{
    protected ?Application $app = null;

    protected string $homeUrl;

    protected string $postUrl;

    protected int $viewerId;

    public static function setUpDatabase(): void
    {
        $app = static::bootBenchmarkApp();

        try {
            static::assertSafeDatabase($app);

            Artisan::call('view:clear');
            Artisan::call('view:cache');
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed', ['--class' => GroupsSeeder::class]);

            static::seedForumData();
        } finally {
            static::closeBenchmarkApp($app);
        }
    }

    public static function tearDownDatabase(): void
    {
        $database = getenv('WATERHOLE_BENCHMARK_DB_DATABASE');

        if ($database || static::databaseConnection() !== 'sqlite') {
            return;
        }

        @unlink(static::defaultBenchmarkDatabase());
    }

    protected function setUpRequest(): void
    {
        $this->app = static::bootBenchmarkApp();

        $this->viewerId = User::where('name', 'Benchmark Viewer')->value('id');
        $post = Post::oldest('id')->firstOrFail();

        $this->homeUrl = route('waterhole.home');
        $this->postUrl = route('waterhole.posts.show', $post);
    }

    public function tearDownRequest(): void
    {
        static::closeBenchmarkApp($this->app);
        $this->app = null;
    }

    protected static function bootBenchmarkApp(): Application
    {
        $env = [
            'APP_ENV=testing',
            'APP_DEBUG=false',
            'APP_KEY=AckfSECXIvnK5r28GVIWUAxmbBSjTsmF',
            'BLAZE_DEBUG=false',
            'CACHE_STORE=array',
            'DB_CONNECTION=' . static::databaseConnection(),
            'DB_DATABASE=' . static::databaseName(),
            'MAIL_MAILER=array',
            'QUEUE_CONNECTION=sync',
            'SESSION_DRIVER=array',
            'TELESCOPE_ENABLED=false',
        ];

        foreach (['DB_HOST', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD'] as $key) {
            if (getenv("WATERHOLE_BENCHMARK_$key") !== false) {
                $env[] = "$key=" . getenv("WATERHOLE_BENCHMARK_$key");
            }
        }

        $app = Testbench::create(
            options: [
                'enables_package_discoveries' => true,
                'extra' => [
                    'env' => $env,
                ],
            ],
        );

        $app['config']->set('auth.providers.users.model', User::class);
        $app->make(ConsoleKernel::class)->bootstrap();

        return $app;
    }

    protected static function closeBenchmarkApp(?Application $app): void
    {
        if (!$app) {
            return;
        }

        $app->terminate();
        $app->flush();
        Facade::clearResolvedInstances();
    }

    protected function request(string $url): void
    {
        Auth::guard()->login(User::findOrFail($this->viewerId));

        $request = Request::create($url);
        $response = $this->app->make(HttpKernel::class)->handle($request);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                "Expected [200] status code but received [{$response->getStatusCode()}]: " .
                    substr(strip_tags($response->getContent()), 0, 500),
            );
        }

        $response->getContent();
        $this->app->make(HttpKernel::class)->terminate($request, $response);
    }

    private static function databaseConnection(): string
    {
        return getenv('WATERHOLE_BENCHMARK_DB_CONNECTION') ?: 'sqlite';
    }

    private static function databaseName(): string
    {
        return getenv('WATERHOLE_BENCHMARK_DB_DATABASE') ?: static::defaultBenchmarkDatabase();
    }

    private static function defaultBenchmarkDatabase(): string
    {
        if (static::databaseConnection() !== 'sqlite') {
            return 'waterhole_benchmark';
        }

        $class = str_replace('\\', '-', static::class);
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $class . '.sqlite';

        if (!file_exists($path)) {
            touch($path);
        }

        return $path;
    }

    private static function seedForumData(): void
    {
        $viewer = User::factory()->create(['name' => 'Benchmark Viewer']);

        $channels = Channel::factory(4)->public()->create();
        $users = User::factory(20)->create()->push($viewer);
        $posts = collect();

        foreach ($channels as $index => $channel) {
            ChannelUser::create([
                'channel_id' => $channel->id,
                'user_id' => $viewer->id,
                'notifications' => $index === 3 ? 'ignore' : null,
                'followed_at' => $index === 0 ? now()->subDays(5) : null,
            ]);
        }

        for ($i = 0; $i < 80; $i++) {
            $posts->push(
                Post::factory()
                    ->for($channels[$i % 3])
                    ->for($users->random())
                    ->create([
                        'comment_count' => $i % 5,
                        'last_activity_at' => now()->subMinutes($i * 7),
                    ]),
            );
        }

        foreach ($posts->take(30) as $index => $feedPost) {
            PostUser::create([
                'post_id' => $feedPost->id,
                'user_id' => $viewer->id,
                'last_read_at' => $index % 3 === 0 ? now()->subDays(2) : now(),
                'notifications' => $index % 17 === 0 ? 'ignore' : null,
                'followed_at' => $index % 7 === 0 ? now()->subDay() : null,
            ]);
        }

        foreach ($posts->take(5) as $feedPost) {
            Bookmark::create([
                'user_id' => $viewer->id,
                'content_type' => $feedPost->getMorphClass(),
                'content_id' => $feedPost->id,
            ]);
        }

        $post = $posts->first();

        for ($i = 0; $i < 30; $i++) {
            Comment::factory()
                ->for($post)
                ->for($users->random())
                ->create(['created_at' => now()->subMinutes($i * 3)]);
        }
    }

    private static function assertSafeDatabase(Application $app): void
    {
        $connection = $app['config']->get('database.default');
        $config = $app['config']->get("database.connections.$connection");

        if ($config['driver'] === 'sqlite') {
            return;
        }

        if (!preg_match('/(bench|test)/i', $config['database'] ?? '')) {
            throw new \RuntimeException(
                'Refusing to run benchmark migrations against [' .
                    ($config['database'] ?? '') .
                    ']. Set WATERHOLE_BENCHMARK_DB_DATABASE to a dedicated test/bench database.',
            );
        }
    }
}
