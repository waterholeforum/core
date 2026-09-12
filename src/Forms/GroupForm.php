<?php

declare(strict_types=1);

namespace Waterhole\Forms;

use Waterhole\Models\Group;

class GroupForm extends Form
{
    public function __construct(Group $group)
    {
        parent::__construct($group);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\GroupForm::class)->components([
            'model' => $this->model,
        ]);
    }
}
