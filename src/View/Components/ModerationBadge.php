<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Flag;
use Waterhole\Models\User;
use Waterhole\View\Components\Concerns\Streamable;

class ModerationBadge extends Component
{
    use Streamable;

    public int $count;

    public function __construct(public User $user)
    {
        $this->count = Flag::query()
            ->visible($user)
            ->pending()
            ->select('subject_type', 'subject_id')
            ->distinct()
            ->count();
    }

    public function render()
    {
        return <<<'blade'
            <span
                {{ $attributes->class('badge bg-activity')->merge([
                    'data-notifications-popup-target' => 'badge',
                    'hidden' => !$count
                ]) }}
            >{{ $count }}</span>
        blade;
    }
}
