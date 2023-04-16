<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tonysm\TurboLaravel\Http\TurboResponseFactory;
use Waterhole\Actions\Action;
use Waterhole\Extend;
use Waterhole\View\Components\Alert;
use Waterhole\View\TurboStream;

/**
 * Controller for endpoints related to the Actions system.
 */
final class ActionController extends Controller
{
    /**
     * Prompt the user to confirm that they want to run an action.
     *
     * This view displays a dialog with a form containing the action's custom
     * confirmation body, and buttons to confirm the action or go back to the
     * previous page.
     */
    public function confirm(Request $request)
    {
        $models = $this->getModels($request);
        $action = $this->getAction($models, $request);

        return view('waterhole::confirm-action', [
            'action' => $action,
            'actionable' => $request->input('actionable'),
            'models' => $models,
        ]);
    }

    /**
     * Run an action.
     */
    public function run(Request $request)
    {
        $models = $this->getModels($request);
        $action = $this->getAction($models, $request);

        // If the action requires confirmation, but it has not been confirmed
        // by pressing the submit button in the confirmation view, then we
        // will redirect the user back to the confirmation view with all the
        // same input.
        if ($action->confirm && !$request->has('confirmed')) {
            return redirect()->route('waterhole.action.create', $request->input());
        }

        // Attempt to run the action. If we catch a validation exception, we
        // will redirect the user back to the confirmation view with all the
        // same input, and the confirmation view can render the errors.
        try {
            $response = $action->run($models);
        } catch (ValidationException $exception) {
            throw $exception->redirectTo(route('waterhole.action.create', $request->input()));
        }

        // If the client supports Turbo Streams, we will return streams for
        // each of the actioned models from the action class. We will also
        // add on streams for any alerts that the action may have flashed.
        if (
            $request->wantsTurboStream() &&
            ($streams = $models->flatMap(fn($item) => $action->stream($item))->all())
        ) {
            foreach (['success', 'warning', 'danger'] as $type) {
                if ($message = session()->get($type)) {
                    $streams[] = TurboStream::append(
                        new Alert(type: $type, message: $message),
                        '#alerts',
                    );
                }
            }

            session()->ageFlashData();

            return TurboResponseFactory::makeStream(implode(PHP_EOL, $streams));
        }

        if ($response) {
            return $response;
        }

        return redirect($request->get('return', url()->previous()));
    }

    /**
     * Retrieve the model instances that are to be actioned.
     *
     * The model instances are retrieved based on the "actionable" and the "id"
     * inputs in the form submission.
     */
    private function getModels(Request $request): Collection
    {
        $actionable = $request->input('actionable');

        if (!($model = Extend\Actionables::get($actionable))) {
            abort(400, "The actionable [$actionable] does not exist");
        }

        $models = $model::findMany((array) $request->input('id'));

        if (!$models->count()) {
            abort(400, 'No models found.');
        }

        return $models;
    }

    /**
     * Get an instance of the action that is to be applied.
     *
     * The action that is to be applied is specified in the "action_class"
     * input. However, before instantiating it, we ensure that it is one of
     * the registered actions that is allowed to be applied to these models.
     */
    private function getAction(Collection $models, Request $request): Action
    {
        $actions = Extend\Actions::for($models);
        $requestedAction = $request->get('action_class');

        foreach ($actions as $action) {
            if (get_class($action) === $requestedAction) {
                return $action;
            }
        }

        abort(403, 'This action cannot be applied.');
    }
}
