<?php

namespace Waterhole\Views\Components\Concerns;

use ReflectionClass;

use function Tonysm\TurboLaravel\dom_id;

trait Streamable
{
    public function streamableClassName(): ?string
    {
        $name = (new ReflectionClass($this))->getShortName();
        $properties = $this->extractPublicProperties();

        if ($model = collect($properties)->first(fn($value) => is_object($value))) {
            return dom_id($model, $name);
        }

        return $name;
    }

    /**
     * Automatically set the class attribute on the attribute bag.
     */
    public function data(): array
    {
        $this->setClassAttribute();

        return parent::data();
    }

    /**
     * Automatically set the class attribute on the attribute bag.
     */
    public function withAttributes(array $attributes): static
    {
        parent::withAttributes($attributes);

        $this->setClassAttribute();

        return $this;
    }

    private function setClassAttribute(): void
    {
        $this->attributes = $this->attributes ?: $this->newAttributeBag();

        if ($class = $this->streamableClassName()) {
            $this->attributes['class'] .= " $class";
        }
    }
}
