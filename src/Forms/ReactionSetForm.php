<?php

namespace Waterhole\Forms;

use Waterhole\Models\ReactionSet;

class ReactionSetForm extends Form
{
    public function __construct(ReactionSet $reactionSet)
    {
        parent::__construct($reactionSet);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\ReactionSetForm::class)->components(['model' => $this->model]);
    }
}
