<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Waterhole\Models\Model;

class FlagSummary extends Component
{
    public function __construct(public Model $subject)
    {
    }

    public function render()
    {
        return $this->subject->pendingFlags
            ->groupBy('reason')
            ->map(function ($group, $reason) {
                $label = Lang::has($key = "waterhole::forum.report-reason-$reason-label")
                    ? __($key)
                    : Str::headline($reason);
                $count = $group->count();
                return $count > 1 ? "$label x$count" : $label;
            })
            ->join(', ');
    }
}
