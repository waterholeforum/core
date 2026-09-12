<?php

declare(strict_types=1);

namespace Waterhole\View\Components;

class CreatePostMenuItem extends CreatePostButton
{
    public function shouldRender(): bool
    {
        return $this->enabled();
    }

    public function render()
    {
        return $this->view('waterhole::components.create-post-menu-item');
    }
}
