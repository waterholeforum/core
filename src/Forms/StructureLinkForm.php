<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\StructureLink;

class StructureLinkForm extends Form
{
    public function __construct(StructureLink $link)
    {
        parent::__construct($link);
    }

    public function fields(): array
    {
        return Extend\StructureLinkForm::components(['model' => $this->model]);
    }
}
