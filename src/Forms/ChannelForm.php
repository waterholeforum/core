<?php

namespace Waterhole\Forms;

use Waterhole\Models\Channel;

class ChannelForm extends Form
{
    public function __construct(Channel $channel)
    {
        parent::__construct($channel);
    }

    public function fields(): array
    {
        return resolve(\Waterhole\Extend\Forms\ChannelForm::class)->components(['model' => $this->model]);
    }
}
