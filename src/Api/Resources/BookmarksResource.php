<?php

namespace Waterhole\Api\Resources;

use Tobyz\JsonApiServer\Context;
use Waterhole\Extend\Api\BookmarksResource as BookmarksResourceExtender;
use Waterhole\Models\Bookmark;

class BookmarksResource extends ExtendableResource
{
    public function __construct(BookmarksResourceExtender $extender)
    {
        $this->extender = $extender;
    }

    public function type(): string
    {
        return 'bookmarks';
    }

    public function newModel(Context $context): object
    {
        return new Bookmark();
    }
}
