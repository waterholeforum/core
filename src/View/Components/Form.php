<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\Forms\FormSection;

class Form extends Component
{
    private static int $instance = 0;

    public string $formMethod;
    public ?string $spoofMethod;
    public array $sections = [];

    public function __construct(
        public array $fields = [],
        string $method = 'POST',
        public ?string $cancelUrl = null,
        public ?string $submitLabel = null,
        public array $submitAttributes = [],
        public array $panelAttributes = [],
    ) {
        $method = strtoupper($method);

        $this->formMethod = $method === 'GET' ? 'GET' : 'POST';
        $this->spoofMethod = in_array($method, ['GET', 'POST']) ? null : $method;

        $this->submitLabel =
            $submitLabel ??
            match ($method) {
                'POST' => __('waterhole::system.create-button'),
                'DELETE' => __('waterhole::system.delete-button'),
                default => __('waterhole::system.save-changes-button'),
            };

        $this->cancelUrl = $cancelUrl ?: url()->previous();

        $this->resolveSections();
    }

    public function render()
    {
        return $this->view('waterhole::components.form');
    }

    public function resolvePanelAttributes(?string $defaultClass = null): ComponentAttributeBag
    {
        $attributes = new ComponentAttributeBag($this->panelAttributes);

        if ($attributes->has('class')) {
            return $attributes;
        }

        return $attributes->class($defaultClass);
    }

    private function resolveSections(): void
    {
        $sections = [];
        $orphans = [];

        foreach ($this->fields as $field) {
            if ($field instanceof FormSection) {
                $sections[] = $field;
            } elseif ($field) {
                $orphans[] = $field;
            }
        }

        if ($orphans) {
            $sections[] = new FormSection(
                __('waterhole::system.form-general-section-title'),
                $orphans,
            );
        }

        $seenIds = [];
        $prefix = implode('-', array_filter(['tab', self::$instance++]));

        foreach ($sections as $index => $section) {
            if (!$section->shouldRender()) {
                continue;
            }

            $slug = Str::slug($section->title);
            $id = $slug ?: 'section-' . ($index + 1);
            $seenIds[$id] = ($seenIds[$id] ?? 0) + 1;
            $suffix = $seenIds[$id] > 1 ? '-' . $seenIds[$id] : '';

            $this->sections[] = [
                'title' => $section->title,
                'panelId' => "$prefix-$id$suffix",
                'components' => $section->components,
            ];
        }
    }
}
