<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\ReactionType;

class ReactionTypeForm extends Form
{
    public function __construct(ReactionType $reactionType)
    {
        parent::__construct($reactionType);
    }

    public function fields(): array
    {
        return Extend\ReactionTypeForm::components(['model' => $this->model]);
    }
}
