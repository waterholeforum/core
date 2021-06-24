<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;

/**
 * A list of components to render in the document <head>.
 */
abstract class DocumentHead
{
    use OrderedList;
}
