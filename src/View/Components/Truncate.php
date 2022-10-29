<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

use function Waterhole\emojify;
use function Waterhole\truncate_html;

class Truncate extends Component
{
    public string $excerpt;
    public bool $truncated;

    public function __construct(public string $html, public int $limit = 500)
    {
        $this->excerpt = (string) emojify(truncate_html($html, $limit));
        $this->truncated = str_ends_with(strip_tags($this->excerpt), '...');
    }

    public function render(): string
    {
        return <<<'blade'
            {!! $excerpt !!}
            @if ($truncated) {{ $slot }} @endif
        blade;
    }
}
