<?php

namespace Waterhole\View\Components;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Waterhole\Models\ReactionSet;

class ReactionSetPicker extends Component
{
    public Collection $reactionSets;

    public function __construct(
        public ?string $value = null,
        public ?ReactionSet $default = null,
        public ?bool $enabled = null,
        public ?int $selectedId = null,
    ) {
        $this->reactionSets = ReactionSet::all();
        $this->value ??= $this->resolveValue();
    }

    public function render()
    {
        return $this->view('waterhole::components.reaction-set-picker');
    }

    public static function rule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if ($value === null || $value === '' || $value === 'default' || $value === 'none') {
                return;
            }

            if (!ReactionSet::whereKey($value)->exists()) {
                $fail(__('validation.exists', ['attribute' => $attribute]));
            }
        };
    }

    public static function resolveSelection(mixed $selection): array
    {
        $enabled = $selection !== 'none';
        $reactionSetId = in_array($selection, [null, '', 'default', 'none'], true)
            ? null
            : $selection;

        return [$enabled, $reactionSetId];
    }

    private function resolveValue(): ?string
    {
        if ($this->enabled === null) {
            return $this->value;
        }

        if (!$this->enabled) {
            return 'none';
        }

        return $this->selectedId ? (string) $this->selectedId : 'default';
    }
}
