<?php

namespace Waterhole\Forms;

use Waterhole\Models\StructureLink;

class StructureLinkForm extends Form
{
    public function __construct(StructureLink $link)
    {
        parent::__construct($link);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\StructureLinkForm::class)->components(['model' => $this->model]);
    }
}
