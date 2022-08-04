<?php

namespace Waterhole\Views\Components\Concerns;

use ReflectionClass;
use function Tonysm\TurboLaravel\dom_id;

trait Streamable
{
    public function id(): ?string
    {
        $name = (new ReflectionClass($this))->getShortName();
        $properties = $this->extractPublicProperties();

        if ($model = reset($properties)) {
            return dom_id($model, $name);
        }

        return $name;
    }

    /**
     * Automatically set the ID attribute on the attribute bag.
     */
    public function data(): array
    {
        $this->setIdAttribute();

        return parent::data();
    }

    /**
     * Automatically set the ID attribute on the attribute bag.
     */
    public function withAttributes(array $attributes): static
    {
        parent::withAttributes($attributes);

        $this->setIdAttribute();

        return $this;
    }

    private function setIdAttribute(): void
    {
        $this->attributes = $this->attributes ?: $this->newAttributeBag();

        if ($id = $this->id()) {
            $this->attributes['id'] = $id;
        }
    }
}
