<?php

namespace Waterhole\Views\Components\Concerns;

use ReflectionClass;

use function Tonysm\TurboLaravel\dom_id;

trait Streamable
{
    public function id(): ?string
    {
        $properties = $this->extractPublicProperties();

        if ($model = reset($properties)) {
            return dom_id($model, (new ReflectionClass($this))->getShortName());
        }

        return null;
    }

    /**
     * Automatically set the ID attribute on the attribute bag.
     */
    public function data(): array
    {
        $data = parent::data();

        $this->setIdAttribute();

        return $data;
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
        if ($id = $this->id()) {
            $this->attributes['id'] = $id;
        }
    }
}
