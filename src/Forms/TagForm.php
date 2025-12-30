<?php

namespace Waterhole\Forms;

use Waterhole\Models\Tag;

class TagForm extends Form
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\TagForm::class)->components(['model' => $this->model]);
    }
}
