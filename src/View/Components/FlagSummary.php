<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Waterhole\Models\Model;

class FlagSummary extends Component
{
    public string $summary;

    public function __construct(public Model $subject)
    {
        $flags = $this->subject->pendingFlags;
        $total = $flags->count();

        $this->summary = $flags
            ->groupBy('reason')
            ->sortByDesc(fn($group) => $group->count())
            ->map(function ($group, $reason) use ($total) {
                $label = Lang::has($key = "waterhole::forum.report-reason-$reason-label")
                    ? __($key)
                    : Str::headline($reason);
                $count = $group->count();

                if ($total <= 1) {
                    return $label;
                }

                return sprintf('%s (%d)', $label, $count);
            })
            ->join(', ');
    }

    public function render()
    {
        return $this->summary;
    }
}
