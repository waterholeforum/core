<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Waterhole\Formatter\Formatter;

use function Waterhole\emojify;

/**
 * Controller to render plain-text content as HTML.
 *
 * This is used for the "preview" function in the text editor.
 */
class FormatController extends Controller
{
    public function __construct(private Formatter $formatter) {}

    public function __invoke(Request $request): string
    {
        $xml = $this->formatter->parse((string) $request->getContent());

        return $this->formatter->render($xml);
    }
}
