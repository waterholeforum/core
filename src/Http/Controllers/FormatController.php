<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Waterhole\Formatter\Context;
use Waterhole\Formatter\Formatter;

/**
 * Controller to render plain-text content as HTML.
 *
 * This is used for the "preview" function in the text editor.
 */
class FormatController extends Controller
{
    public function __construct(
        private Formatter $formatter,
    ) {}

    public function __invoke(Request $request): string
    {
        $context = new Context(user: $request->user());
        $xml = $this->formatter->parse((string) $request->getContent(), $context);

        return $this->formatter->render($xml, $context);
    }
}
