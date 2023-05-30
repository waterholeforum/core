<?php

namespace Waterhole\Console;

use Exception;
use Illuminate\Console\Command;

class ReformatCommand extends Command
{
    protected $signature = 'waterhole:reformat';

    protected $description = 'Reparse formatted content';

    private static array $modelAttributes = [];

    public function handle()
    {
        $this->info('The following content will be reformatted:');

        $this->table(
            ['Model', 'Attributes'],
            collect(static::$modelAttributes)->map(
                fn($attributes, $model) => [$model, implode(', ', $attributes)],
            ),
        );

        if (!$this->confirm('Proceed?')) {
            return;
        }

        foreach (static::$modelAttributes as $modelClass => $attributes) {
            $this->info("Reformatting $modelClass...");

            $query = $modelClass::query();
            $count = $query->count();

            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $query->chunk(1000, function ($models) use ($bar, $modelClass, $attributes) {
                foreach ($models as $model) {
                    $modelString = $modelClass . '#' . $model->getKey();

                    try {
                        foreach ($attributes as $attribute) {
                            try {
                                $model->setAttribute($attribute, $model->getAttribute($attribute));
                            } catch (Exception $e) {
                                $this->newLine();
                                $this->warn(
                                    "Error parsing $modelString $attribute: " . $e->getMessage(),
                                );
                            }
                        }

                        $model->saveQuietly();
                    } catch (Exception $e) {
                        $this->newLine();
                        $this->warn("Error saving $modelString: " . $e->getMessage());
                    }

                    $bar->advance();
                }
            });

            $bar->finish();
            $this->newLine(2);
        }

        $this->info('Content reformatted!');
    }

    public static function addModelAttribute(string $model, string $attribute): void
    {
        static::$modelAttributes[$model][] = $attribute;
    }
}
