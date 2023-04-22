<?php

namespace Waterhole\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;
use Waterhole\Console\Concerns\ValidatesInput;
use Waterhole\Waterhole;

class MakeExtensionCommand extends Command
{
    use ValidatesInput;

    protected $signature = 'waterhole:make:extension {name : The name of the extension package}
        {--path=extensions : The location where the extension should be created}';

    protected $description = 'Create a new Waterhole extension';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->input->getArgument('name');

        if (
            !preg_match('~^[a-z0-9]([_.-]?[a-z0-9]+)*/[a-z0-9](([_.]?|-{0,2})[a-z0-9]+)*$~', $name)
        ) {
            $this->error('The name must be a valid Composer package name.');

            return false;
        }

        [$vendor, $project] = explode('/', $name);

        $path = $this->laravel->basePath($this->input->getOption('path') . "/$vendor-$project");

        if ($this->files->exists($path)) {
            $this->error('Extension already exists.');

            return false;
        }

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), recursive: true, force: true);
        }

        $this->files->copyDirectory(__DIR__ . '/stubs/extension', $path);

        $replacements = [
            '{{ namespace }}' => ($namespace = Str::studly($vendor) . '\\' . Str::studly($project)),
            '{{ namespace_escaped }}' => addslashes($namespace),
            '{{ prefix }}' => ($prefix = Str::studly($project)),
            '{{ name }}' => $name,
            '{{ waterhole_version }}' => Waterhole::VERSION,
        ];

        foreach ($this->files->allFiles($path) as $file) {
            $this->files->replaceInFile(
                array_keys($replacements),
                array_values($replacements),
                $file,
            );
        }

        $this->files->move(
            "$path/src/ServiceProvider.stub",
            "$path/src/{$prefix}ServiceProvider.php",
        );

        $this->info(sprintf('Extension [%s] created successfully.', $path));

        $this->addComposerRepository();

        $this->installExtension($name);
    }

    private function addComposerRepository()
    {
        $file = $this->laravel->basePath('composer.json');

        $decoded = json_decode($this->files->get($file), true);

        $repository = [
            'type' => 'path',
            'url' => 'extensions/*',
        ];

        if (in_array($repository, $decoded['repositories'])) {
            return;
        }

        $decoded['repositories'][] = $repository;

        $this->files->put($file, json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        $this->info('Path repository added to composer.json.');
    }

    private function installExtension(string $name)
    {
        $this->info('Installing your extension...');

        $args = [
            (new PhpExecutableFinder())->find(),
            $this->laravel->basePath('vendor/bin/composer'),
            'require',
            "$name:dev-main",
        ];

        system(implode(' ', $args));
    }
}
