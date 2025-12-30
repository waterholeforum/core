<?php

namespace Waterhole\Forms;

use Waterhole\Models\ReactionType;

class ReactionTypeForm extends Form
{
    public function __construct(ReactionType $reactionType)
    {
        parent::__construct($reactionType);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\ReactionTypeForm::class)->components([
            'model' => $this->model,
        ]);
    }
}
