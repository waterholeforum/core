<?php

namespace Waterhole\Forms;

use Waterhole\Extend;
use Waterhole\Models\Channel;

class ChannelForm extends Form
{
    public function __construct(Channel $channel)
    {
        parent::__construct($channel);
    }

    public function fields(): array
    {
        return Extend\ChannelForm::components(['model' => $this->model]);
    }
}
