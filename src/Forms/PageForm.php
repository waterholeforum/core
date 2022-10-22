<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\Page;

class PageForm extends Form
{
    public function __construct(Page $page)
    {
        parent::__construct($page);
    }

    public function fields(): array
    {
        return Extend\PageForm::components(['page' => $this->model]);
    }
}
