<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Schema\Field\ToOne;
use Waterhole\Extend\Support\Resource;

/**
 * Mentions JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for mentions.
 */
class MentionsResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->fields
            ->add(ToOne::make('content')->type(['posts', 'comments']), 'content')

            ->add(
                ToOne::make('mentionable')
                    ->type(['users', 'groups'])
                    ->includable(),
                'mentionable',
            );
    }
}
