<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Waterhole\Extend\Action;
use Waterhole\Extend\Actionable;

class ActionController extends Controller
{
    protected function getItems(Request $request)
    {
        abort_unless($class = Actionable::getItems()[$request->get('actionable')] ?? null, 400);

        return $class::query()->findMany((array) $request->get('id'));
    }

    protected function getAction($items, Request $request)
    {
        $actions = Action::for($items);
        $requestedAction = $request->get('action');

        foreach ($actions as $action) {
            if (get_class($action) === $requestedAction) {
                return $action;
            }
        }

        abort(403, 'This action cannot be applied.');
    }

    public function confirm(Request $request)
    {
        $items = $this->getItems($request);
        $action = $this->getAction($items, $request);
        $confirmation = $action->confirmation($items);

        return view('waterhole::confirm-action', [
            'confirmation' => $confirmation,
            'action' => $action,
            'actionable' => $request->get('actionable'),
            'items' => $items,
        ]);
    }

    public function run(Request $request)
    {
        $items = $this->getItems($request);
        $action = $this->getAction($items, $request);

        if ($response = $action->run($items, $request)) {
            return $response;
        }

        return redirect($request->get('redirect', url()->previous()));
    }
}
