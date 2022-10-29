<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\Group;

class GroupForm extends Form
{
    public function __construct(Group $group)
    {
        parent::__construct($group);
    }

    public function fields(): array
    {
        return Extend\GroupForm::components(['model' => $this->model]);
    }
}
