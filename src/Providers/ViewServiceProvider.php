<?php

namespace Waterhole\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Waterhole\Waterhole;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'waterhole');

        Blade::componentNamespace('Waterhole\\Views\\Components', 'waterhole');

        $this->registerComponentsDirective();

        if (Waterhole::isWaterholeRoute()) {
            Paginator::defaultView('waterhole::pagination.default');
            Paginator::defaultSimpleView('waterhole::pagination.simple-default');
        }
    }

    private function registerComponentsDirective(): void
    {
        /**
         * The `@components` directive loops through an array of components and
         * renders them, optionally passing in data. Components can be any of:
         *
         * - An `Illuminate\View\Component` instance
         * - The name of a `Illuminate\View\Component` class
         * - The name of a view
         *
         * If a component/view can't be found, and debug mode is on, a warning
         * will be logged to the browser console.
         */
        Blade::directive('components', function (string $expression): string {
            [$components, $data] = str_contains($expression, ',')
                ? array_map('trim', explode(',', $expression, 2))
                : [$expression, ''];

            return implode("\n", [
                '<?php foreach (' . $components . ' as $component): ?>',
                '<?php unset($instance); ?>',
                '<?php if ($component instanceof Closure): ?>',
                '<?php $component = $component(' . $data . '); ?>',
                '<?php endif; ?>',
                '<?php if ($component instanceof Illuminate\View\Component): ?>',
                '<?php $instance = $component; ?>',
                '<?php elseif (class_exists($component)): ?>',
                '<?php $instance = $__env->getContainer()->make($component, ' .
                ($data ?: '[]') .
                '); ?>',
                '<?php elseif ($__env->getContainer()->make(Illuminate\View\Factory::class)->exists($component)): ?>',
                '<?php $instance = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, [\'view\' => $component, \'data\' => ' .
                ($data ?: '[]') .
                ']); ?>',
                '<?php elseif (config(\'app.debug\')): ?>',
                '<script>console.warn(\'Component [<?php echo e(addslashes($component)); ?>] not found\')</script>',
                '<?php endif; ?>',
                '<?php if (isset($instance) && $instance->shouldRender()): ?>',
                '<?php $__env->startComponent($instance->resolveView(), $instance->data()); ?>',
                '<?php echo $__env->renderComponent(); ?>',
                '<?php endif; ?>',
                '<?php endforeach; ?>',
            ]);
        });

        Blade::directive('return', function (string $expression): string {
            return '<?php echo Waterhole\return_field(' . $expression . '); ?>';
        });
    }
}
