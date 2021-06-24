<?php

namespace Waterhole\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'waterhole');

        Blade::componentNamespace('Waterhole\\Views\\Components', 'waterhole');

        $this->registerComponentsDirective();
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
                '<?php $component = $__env->getContainer()->make($component, '.($data ?: '[]').'); ?>',
                '<?php else: ?>',
                '<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, [\'view\' => $component, \'data\' => '.($data ?: '[]').']); ?>',
                '<?php endif; ?>',
                '<?php if ($component->shouldRender()): ?>',
                '<?php $__env->startComponent($component->resolveView(), $component->data()); ?>',
                '<?php echo $__env->renderComponent(); ?>',
                '<?php endif; ?>',
                '<?php endforeach; ?>',
            ]);
        });
    }
}
