<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\ReactionSet;

class ReactionSetForm extends Form
{
    public function __construct(ReactionSet $reactionSet)
    {
        parent::__construct($reactionSet);
    }

    public function fields(): array
    {
        return Extend\ReactionSetForm::components(['model' => $this->model]);
    }
}
