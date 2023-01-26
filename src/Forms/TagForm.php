<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\Tag;

class TagForm extends Form
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function fields(): array
    {
        return Extend\TagForm::components(['model' => $this->model]);
    }
}
