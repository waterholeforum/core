<?php

namespace Waterhole\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;
use Waterhole\Models\Model;
use Waterhole\Models\User;

/**
 * Base class for an Action.
 *
 * Actions are a mechanism for performing tasks on one or more models – for
 * example, deleting comments, or locking a post. Each item's context menu is
 * made up of a list of Actions.
 *
 * To define a new action, extend this class, and override and implement methods
 * as required. Use the `Waterhole\Extend\Core\Actions` extender to register an
 * action class for a model.
 */
abstract class Action
{
    /**
     * Whether the action can be applied to multiple models at once.
     */
    public bool $bulk = false;

    /**
     * Whether the action requires confirmation or user input before it is run.
     */
    public bool $confirm = false;

    /**
     * Whether the action is destructive, and should have a red appearance.
     */
    public bool $destructive = false;

    /**
     * Whether the action can logically be applied to the given model.
     */
    public function appliesTo(Model $model): bool
    {
        return true;
    }

    /**
     * Whether a user is allowed to apply the action to the given model.
     *
     * By default, the action can be applied if the user is logged-in.
     */
    public function authorize(?User $user, Model $model): bool
    {
        return (bool) $user;
    }

    /**
     * Whether the action should be listed in a menu for the given models.
     */
    public function shouldRender(Collection $models, ?string $context = null): bool
    {
        return true;
    }

    /**
     * The label to be displayed in the action button.
     */
    abstract public function label(Collection $models): string;

    /**
     * The name of an icon to be displayed in the action button.
     *
     * @see \Waterhole\View\Components\Icon
     */
    public function icon(Collection $models): ?string
    {
        return null;
    }

    /**
     * Any extra attributes for the action button.
     */
    public function attributes(Collection $models): array
    {
        return [];
    }

    /**
     * Render the action button.
     */
    public function render(Collection $models, array $attributes, bool $tooltip = false): HtmlString
    {
        $attributes = (new ComponentAttributeBag($attributes))->merge($this->attributes($models));

        // If the action requires confirmation, we will override the form's
        // method and action to take the user to the confirmation route.
        if ($this->confirm) {
            $attributes = $attributes->merge([
                'formmethod' => 'GET',
                'formaction' => route('waterhole.actions.create'),
                'data-turbo-frame' => 'modal',
            ]);
        }

        if ($this->destructive) {
            $attributes = $attributes->class('color-danger');
        }

        $class = e(static::class);
        $content = $this->renderContent($models, $tooltip);

        return new HtmlString(
            <<<html
                <button type="submit" name="action_class" value="$class" $attributes>$content</button>
            html
            ,
        );
    }

    /**
     * Render the content of the action button.
     */
    protected function renderContent(Collection $models, bool $tooltip = false): HtmlString
    {
        $label = e($this->label($models));
        $icon = ($iconName = $this->icon($models))
            ? svg($iconName, 'icon icon-' . $iconName)->toHtml()
            : '';
        $tag = $tooltip ? 'ui-tooltip' : 'span';
        $ellipsis = !$tooltip && $this->confirm ? '...' : '';

        return new HtmlString("$icon <$tag>$label$ellipsis</$tag>");
    }

    /**
     * Confirmation message or view to prompt the user with before the action
     * is run.
     */
    public function confirm(Collection $models): null|string|array|HtmlString|View
    {
        return null;
    }

    /**
     * Label to be displayed on the confirmation button.
     */
    public function confirmButton(Collection $models): string
    {
        return 'Confirm';
    }

    /**
     * Run the action on the given models.
     *
     * You can optionally return a response, such as a redirect or a file
     * download. If you don't return anything, Waterhole will keep the user on
     * the current page.
     */
    public function run(Collection $models)
    {
        return null;
    }

    /**
     * Stream partial updates to the page via Turbo Streams.
     *
     * Return an array of <turbo-stream> elements. The default implementation
     * gets streams from the model's `streamRemoved` method if the action is
     * destructive, and the `streamUpdated` method if it isn't.
     *
     * @see \Waterhole\View\TurboStream
     */
    public function stream(Model $model): array
    {
        $method = $this->destructive ? 'streamRemoved' : 'streamUpdated';

        if (method_exists($model, $method)) {
            return $model->$method();
        }

        return [];
    }
}
