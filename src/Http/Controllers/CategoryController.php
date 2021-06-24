<?php

namespace Waterhole\Http\Controllers;

use Illuminate\View\AnonymousComponent;
use Illuminate\View\Compilers\ComponentTagCompiler;
use Nyholm\Psr7\ServerRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('signed')->only('renderComponent');
    }

    public function subscription(string $id)
    {
        $category = waterhole_api(
            (new ServerRequest('GET', 'categories/'.$id))
                ->withQueryParams(['include' => 'subscription'])
        );

        if (request('action') === 'subscribe' || request('action') === 'mute') {
            waterhole_api(
                (new ServerRequest('POST', 'subscriptions'))
                    ->withParsedBody([
                        'data' => [
                            'type' => 'subscriptions',
                            'attributes' => [
                                'isMuted' => request('action') === 'mute'
                            ],
                            'relationships' => [
                                'subscribable' => ['data' => $category->identifier()]
                            ]
                        ]
                    ])
            );
        } elseif ($category->subscription) {
            waterhole_api(new ServerRequest('DELETE', $category->subscription->links->self));
        }

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    protected static $components = ['DiscussionListHeaderItemCategory'];

    public function renderComponent(string $id, string $component)
    {
        if (! in_array($component, static::$components)) {
            abort(404);
        }

        $category = waterhole_api(
            (new ServerRequest('GET', 'categories/'.$id))
                ->withQueryParams(['include' => 'subscription,children,ancestors'])
        );

        $class = (new ComponentTagCompiler)->guessClassName($component);
        $params = compact('category') + (array) request('params');

        if (! class_exists($class)) {
            $component = new AnonymousComponent(view('components.'.$component), $params);
        } else {
            $component = app()->make($class, $params);
        }

        return $component->render()->with($component->data());
    }
}
