<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Http\ApiClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\AnonymousComponent;
use Illuminate\View\Compilers\ComponentTagCompiler;
use Nyholm\Psr7\ServerRequest;

class PostController extends Controller
{
    /**
     * @var ApiClient
     */
    private $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function edit(string $id)
    {
        waterhole_api(
            (new ServerRequest('PATCH', 'posts/'.$id))
                ->withQueryParams(app('waterhole.postsQuery'))
                ->withParsedBody([
                    'data' => [
                        'type' => 'posts',
                        'id' => $id,
                        'attributes' => [
                            'content' => request('content')
                        ]
                    ]
                ])
        );

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    public function reactions(string $id)
    {
        $post = waterhole_api(
            (new ServerRequest('GET', 'posts/'.$id))
                ->withQueryParams(['include' => 'reactions.reactionType'])
        );

        $reactionType = waterhole_api(
            (new ServerRequest('GET', 'reactionTypes/'.(int) request('reactionType')))
        );

        $existingReaction = collect($post->reactions)->first(function ($reaction) use ($reactionType) {
            return $reaction->relationships->user->data->id === (string) Auth::id()
                && $reaction->relationships->reactionType->data->id === $reactionType->id;
        });

        if ($existingReaction) {
            waterhole_api(new ServerRequest('DELETE', $existingReaction->links->self));
        } else {
            waterhole_api(
                (new ServerRequest('POST', 'reactions'))
                    ->withParsedBody([
                        'data' => [
                            'type' => 'reactions',
                            'relationships' => [
                                'post' => ['data' => $post->identifier()],
                                'reactionType' => ['data' => $reactionType->identifier()]
                            ]
                        ]
                    ])
            );
        }

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    public function format()
    {
        $formatter = app('waterhole.formatter.comments');

        $xml = $formatter->parse((string) request('content'));

        return $formatter->render($xml, ['actor' => Auth::user()]);
    }

    protected static $components = ['PostReactions', 'PostEdit', 'PostComment'];

    public function renderComponent(string $id, string $component)
    {
        if (! in_array($component, static::$components)) {
            abort(404);
        }

        $post = waterhole_api(
            (new ServerRequest('GET', 'posts/'.$id))
                ->withQueryParams(app('waterhole.postsQuery'))
        );

        $class = (new ComponentTagCompiler)->guessClassName($component);

        if (! class_exists($class)) {
            $component = new AnonymousComponent(view('components.'.$component), ['post' => $post]);
        } else {
            $component = new $class($post);
        }

        return $component->render()->with($component->data());
    }
}
