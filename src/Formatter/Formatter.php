<?php

namespace Waterhole\Formatter;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Unparser;

/**
 * The Formatter parses plain text content and renders it as HTML.
 *
 * Waterhole uses the TextFormatter library to safely format markup. This class
 * is an abstraction around TextFormatter, enabling extension and caching of its
 * configuration and renderer.
 *
 * @link https://github.com/s9e/TextFormatter
 */
class Formatter
{
    protected array $configurationCallbacks = [];
    protected array $parsingCallbacks = [];
    protected array $renderingCallbacks = [];
    private array $components;

    public function __construct(
        protected Filesystem $files,
        protected string $cacheDir,
        protected Repository $cache,
        protected string $cacheKey,
    ) {
    }

    /**
     * Add a configuration callback to the formatter.
     */
    public function configure(callable $callback): void
    {
        $this->configurationCallbacks[] = $callback;
    }

    /**
     * Parse plain text into an XML document for storage in the database.
     */
    public function parse(string $text, Context $context = null): string
    {
        $parser = $this->getParser();

        foreach ($this->parsingCallbacks as $callback) {
            $callback($parser, $text, $context);
        }

        return $parser->parse($text);
    }

    /**
     * Add a parsing callback to the formatter.
     */
    public function parsing(callable $callback): void
    {
        $this->parsingCallbacks[] = $callback;
    }

    /**
     * Transform a parsed XML document into HTML.
     */
    public function render(string $xml, Context $context = null): string
    {
        $renderer = $this->getRenderer();

        foreach ($this->renderingCallbacks as $callback) {
            $callback($renderer, $xml, $context);
        }

        return $renderer->render($xml);
    }

    /**
     * Add a rendering callback to the formatter.
     */
    public function rendering(callable $callback): void
    {
        $this->renderingCallbacks[] = $callback;
    }

    /**
     * Revert a parsed XML document back into plain text.
     */
    public function unparse(string $xml): string
    {
        return Unparser::unparse($xml);
    }

    /**
     * Flush the formatter from the cache.
     */
    public function flush(): void
    {
        $this->cache->forget($this->cacheKey);

        foreach ($this->files->glob("{$this->cacheDir}/*") as $file) {
            $this->files->delete($file);
        }
    }

    protected function getConfigurator(): Configurator
    {
        $configurator = new Configurator();

        $configurator->tags->onDuplicate('replace');

        $this->configureRenderingCache($configurator);

        foreach ($this->configurationCallbacks as $callback) {
            $callback($configurator);
        }

        return $configurator;
    }

    protected function getComponent(string $name)
    {
        spl_autoload_register(function ($class) {
            if (file_exists($file = "$this->cacheDir/$class.php")) {
                include $file;
            }
        });

        $this->components ??= $this->cache->rememberForever(
            $this->cacheKey,
            fn() => $this->getConfigurator()->finalize(),
        );

        return $this->components[$name];
    }

    protected function getParser(): Parser
    {
        return $this->getComponent('parser');
    }

    protected function getRenderer(): Renderer
    {
        return $this->getComponent('renderer');
    }

    private function configureRenderingCache(Configurator $configurator): void
    {
        $configurator->rendering->engine = 'PHP';
        $configurator->rendering->engine->cacheDir = $this->cacheDir;

        File::ensureDirectoryExists($this->cacheDir);
    }
}
