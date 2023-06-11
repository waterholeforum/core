<?php

namespace Waterhole\View\Components;

class ReactionsCondensed extends Reactions
{
    public function shouldRender(): bool
    {
        return (bool) count($this->reactionTypes);
    }

    public function render()
    {
        if (count($this->reactionSet->reactionTypes) > 1) {
            return $this->view('waterhole::components.reactions-condensed');
        }

        return '<x-waterhole::reactions :model="$model"/>';
    }
}
