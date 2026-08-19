<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureLink;

class StructureCard extends Component
{
    public ?string $description;

    public string $url;

    public function __construct(
        public Channel|Page|StructureLink $content,
    ) {
        $this->description = $content instanceof Page
            ? ($content->description_text ?: $content->body_text)
            : $content->description_text;

        $this->url = $content instanceof StructureLink ? $content->href : $content->url;
    }

    public function render()
    {
        return $this->view('waterhole::components.structure-card');
    }
}
