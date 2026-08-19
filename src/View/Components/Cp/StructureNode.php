<?php

namespace Waterhole\View\Components\Cp;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Group;
use Waterhole\Models\Structure;

class StructureNode extends Component
{
    public Collection $recipients;

    public function __construct(
        public Structure $node,
    ) {
        $node->content->setRelation('structure', $node);
        $this->recipients = $node
            ->permissions
            ->where('ability', 'view')
            ->filter(fn($permission) => $permission->recipient instanceof Group)
            ->map
            ->recipient;
    }

    public function render()
    {
        return $this->view('waterhole::components.cp.structure-node');
    }
}
