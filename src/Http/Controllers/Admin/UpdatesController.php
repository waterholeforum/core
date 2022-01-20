<?php

namespace Waterhole\Http\Controllers\Admin;

use Composer\InstalledVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Waterhole\Http\Controllers\Controller;

class UpdatesController extends Controller
{
    const CACHE_KEY = 'updates';
    const RUN_CACHE_KEY = 'composer_output';

    public function index()
    {
        return view('waterhole::admin.updates.index');
    }

    public function list()
    {
        $packages = $this->getPackages();

        return view('waterhole::admin.updates.list', compact('packages'));
    }

    public function refresh()
    {
        Cache::forget(static::CACHE_KEY);

        return redirect()->route('waterhole.admin.updates.list');
    }

    private function getProcessArguments(Request $request)
    {
        $packages = $request->input('packages');

        return array_filter([
            'vendor/bin/composer',
            'require',
            ...$packages,
            '--update-with-dependencies',
        ]);
    }

    public function start(Request $request)
    {
        $command = implode(' ', $this->getProcessArguments($request));

        return view('waterhole::admin.updates.start', compact('command'));
    }

    public function run(Request $request)
    {
        $process = new Process([
            (new PhpExecutableFinder())->find(),
            '-d memory_limit='.config('waterhole.system.php_memory_limit'),
            ...($arguments = $this->getProcessArguments($request)),
            '--ansi',
        ]);

        $process->setTimeout(null);
        $process->setWorkingDirectory(base_path());

        Cache::put(static::RUN_CACHE_KEY, $output = '$ '.implode(' ', $arguments)."\n");

        $process->run(function ($type, $buffer) use (&$output) {
            Cache::put(static::RUN_CACHE_KEY, $output .= $buffer);
        });

        if ($process->isSuccessful()) {
            Cache::forget(static::CACHE_KEY);
        }
    }

    public function output()
    {
        $output = Cache::get(static::RUN_CACHE_KEY);

        return view('waterhole::admin.updates.output', compact('output'));
    }

    private function getPackages(): Collection
    {
        return Cache::lock(static::CACHE_KEY.'_fetch')->block(120, function () {
            return Cache::remember(static::CACHE_KEY, 60 * 60, function () {
                $process = new Process([
                    (new PhpExecutableFinder())->find(),
                    '-d memory_limit='.config('waterhole.system.php_memory_limit'),
                    'vendor/bin/composer',
                    'outdated',
                    '--direct',
                    '--ansi',
                    '--format=json',
                ]);

                $process->setTimeout(null);
                $process->setWorkingDirectory(base_path());
                $process->run();

                $extensions = InstalledVersions::getInstalledPackagesByType('waterhole-extension');
                $packages = collect(json_decode($process->getOutput(), true)['installed'])
                    ->filter(function ($package) use ($extensions) {
                        return $package['name'] === 'waterhole/core' || in_array($package['name'], $extensions);
                    });

                $installed = collect(json_decode(file_get_contents(base_path('vendor/composer/installed.json')), true)['packages']);

                return $packages->map(function ($package) use ($installed) {
                    $package['latest'] ??= 'dev-master';
                    $package['info'] = $installed->firstWhere('name', $package['name']);
                    $package['changelog'] = $this->getChangelogUrl($package);
                    return $package;
                });
            });
        });
    }

    private function getChangelogUrl(array $package): ?string
    {
        $matches = null;

        if (
            ($package['info']['source']['type'] ?? null) === 'git'
            && preg_match('~^(https://github\.com/.+)\.git$~i', $package['info']['source']['url'] ?? '', $matches)
        ) {
            $headers = @get_headers($url = "$matches[1]/blob/{$package['latest']}/CHANGELOG.md");

            if (! str_contains($headers[0], '404')) {
                return $url;
            }
        }

        return null;
    }
}
