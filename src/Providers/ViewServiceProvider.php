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

        Blade::componentNamespace('Waterhole\\View\\Components', 'waterhole');

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
         * renders them, optionally passing in data. A component can be any of:
         *
         * - An `Illuminate\View\Component` instance
         * - The name of a `Illuminate\View\Component` class
         * - The name of an anonymous component view
         * - `null`, in which case the directive will yield a section named
         *   with the corresponding key
         * - A closure that receives the data and returns any of the above
         *
         * If a component/view can't be found, and debug mode is on, a warning
         * will be logged to the browser console.
         */
        Blade::directive('components', function (string $expression): string {
            [$components, $data] = str_contains($expression, ',')
                ? array_map('trim', explode(',', $expression, 2))
                : [$expression, ''];

            return implode("\n", [
                '<?php $_components = ' . $components . '; ?>',
                '<?php foreach (Waterhole\build_components($_components, ' .
                ($data ?: '[]') .
                ') as $key => $instance): ?>',
                '<?php if ($instance === null && !is_numeric($key) && $__env->hasSection($key)): ?>',
                '<?php echo $__env->yieldContent($key); ?>',
                '<?php endif; ?>',
                '<?php if ($instance instanceof Illuminate\View\Component && $instance->shouldRender()): ?>',
                '<?php $__env->startComponent($instance->resolveView(), $instance->data()); ?>',
                '<?php echo $__env->renderComponent(); ?>',
                '<?php endif; ?>',
                '<?php endforeach; ?>',
            ]);
        });

        Blade::directive('icon', function (string $expression) {
            return '<?php echo Waterhole\icon(' . $expression . '); ?>';
        });

        Blade::directive('return', function (string $expression): string {
            return '<?php echo Waterhole\return_field(' . $expression . '); ?>';
        });
    }
}
