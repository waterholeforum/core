<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Formatter\Formatter;

/**
 * Controller to render plain-text content as HTML.
 *
 * This is used for the "preview" function in the text editor.
 */
class FormatController extends Controller
{
    private Formatter $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function __invoke(Request $request): string
    {
        $xml = $this->formatter->parse((string) $request->getContent());

        return $this->formatter->render($xml, ['actor' => Auth::user()]);
    }
}
