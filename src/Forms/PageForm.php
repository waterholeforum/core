<?php

namespace Waterhole\Forms;

use Waterhole\Models\Page;

class PageForm extends Form
{
    public function __construct(Page $page)
    {
        parent::__construct($page);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\PageForm::class)->components(['model' => $this->model]);
    }
}
