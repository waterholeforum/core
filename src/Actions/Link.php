<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\Models\Model;

/**
 * Base class for a "link" action.
 *
 * Some actions don't actually perform an action at all – they just redirect to
 * another location. A good example is the "edit post" action, which just sends
 * the user to the post's edit route.
 *
 * For cases like this, this class will render the action as an `<a>` element
 * rather than a `<button>`.
 */
abstract class Link extends Action
{
    /**
     * The URL to link to.
     */
    abstract public function url(Model $model): string;

    public function render(
        Collection $models,
        array $attributes,
        bool $tooltip = false,
        bool $ellipsis = false,
    ): HtmlString {
        $link = e($this->url($models[0]));

        $attributes = (new ComponentAttributeBag($attributes))->merge($this->attributes($models));

        $content = $this->renderContent($models, $tooltip);

        return new HtmlString(
            <<<html
                <a href="$link" $attributes>$content</a>
            html
            ,
        );
    }
}
