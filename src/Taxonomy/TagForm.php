<?php

namespace Waterhole\Taxonomy;

use Waterhole\Extend;
use Waterhole\Forms\Form;

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
