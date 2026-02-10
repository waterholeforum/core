<?php

namespace Waterhole\Actions;

use Closure;
use Illuminate\Support\LazyCollection;
use Waterhole\View\Components\MenuDivider;

class ActionsCollection extends LazyCollection
{
    public function __construct($source = null, protected ?Closure $renderable = null)
    {
        parent::__construct($source);
    }

    public function renderable(): static
    {
        $renderable = $this->renderable;

        return $this->filter(
            fn($action) => !$action instanceof Action || !$renderable || $renderable($action),
        );
    }

    public function hasRenderable(): bool
    {
        $renderable = $this->renderable;

        foreach ($this as $action) {
            if ($action instanceof MenuDivider) {
                continue;
            }

            if (!$action instanceof Action || !$renderable || $renderable($action)) {
                return true;
            }
        }

        return false;
    }

    public function filter(?callable $callback = null): static
    {
        return $this->setRenderable(parent::filter($callback))->normalizeDividers();
    }

    public function remember(): static
    {
        return $this->setRenderable(parent::remember());
    }

    public function normalizeDividers(): static
    {
        return $this->newWith(function () {
            $hasAction = false;
            $pendingDivider = null;

            foreach ($this as $action) {
                if ($action instanceof MenuDivider) {
                    if ($hasAction) {
                        $pendingDivider = $action;
                    }

                    continue;
                }

                if ($pendingDivider) {
                    yield $pendingDivider;
                    $pendingDivider = null;
                }

                $hasAction = true;

                yield $action;
            }
        });
    }

    private function newWith($source): static
    {
        return new static($source, $this->renderable);
    }

    private function setRenderable(self $collection): static
    {
        $collection->renderable = $this->renderable;

        return $collection;
    }
}
