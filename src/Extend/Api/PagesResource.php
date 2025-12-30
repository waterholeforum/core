<?php

namespace Waterhole\Extend\Api;

use Tobyz\JsonApiServer\Endpoint;
use Tobyz\JsonApiServer\Schema\Field\Attribute;
use Tobyz\JsonApiServer\Schema\Type;
use Waterhole\Extend\Support\Resource;
use Waterhole\Models\Page;
use function Waterhole\icon;

/**
 * Pages JSON:API resource.
 *
 * Defines fields, endpoints, sorts, filters, and scope for pages.
 */
class PagesResource extends Resource
{
    public function __construct()
    {
        parent::__construct();

        $this->endpoints->add('show', Endpoint\Show::make());

        $this->fields
            ->add('name', Attribute::make('name')->type(Type\Str::make()))

            ->add(
                'iconHtml',
                Attribute::make('iconHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable()
                    ->get(fn(Page $page) => icon($page->icon)),
            )

            ->add(
                'bodyHtml',
                Attribute::make('bodyHtml')
                    ->type(Type\Str::make()->format('html'))
                    ->nullable(),
            )

            ->add('url', Attribute::make('url')->type(Type\Str::make()->format('uri')));
    }
}
