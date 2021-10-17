<?php

namespace Waterhole\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'waterhole');

        Blade::componentNamespace('Waterhole\\Views\\Components', 'waterhole');

        $this->registerComponentsDirective();

        Paginator::defaultView('waterhole::pagination.default');
        Paginator::defaultSimpleView('waterhole::pagination.simple-default');
    }

    private function registerComponentsDirective(): void
    {
        Blade::directive('components', function (string $expression): string {
            [$components, $data] = str_contains($expression, ',')
                ? array_map('trim', explode(',', $expression, 2))
                : [$expression, ''];

            return implode("\n", [
                '<?php foreach ('.$components.' as $component): ?>',
                '<?php if (class_exists($component)): ?>',
                '<?php $instance = $__env->getContainer()->make($component, '.($data ?: '[]').'); ?>',
                '<?php elseif ($__env->getContainer()->make(Illuminate\View\Factory::class)->exists($component)): ?>',
                '<?php $instance = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, [\'view\' => $component, \'data\' => '.($data ?: '[]').']); ?>',
                '<?php elseif (config(\'app.debug\')): ?>',
                '<script>console.warn(\'Component [<?php echo e($component); ?>] not found\')</script>',
                '<?php endif; ?>',
                '<?php if (isset($instance) && $instance->shouldRender()): ?>',
                '<?php $__env->startComponent($instance->resolveView(), $instance->data()); ?>',
                '<?php echo $__env->renderComponent(); ?>',
                '<?php endif; ?>',
                '<?php endforeach; ?>',
            ]);
        });
    }
}
