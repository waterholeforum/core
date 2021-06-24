<?php

namespace Waterhole\Http\Controllers;

use Waterhole\Models\Discussion;
use Waterhole\Http\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\View\AnonymousComponent;
use Illuminate\View\Compilers\ComponentTagCompiler;
use Nyholm\Psr7\ServerRequest;
use Tobyz\JsonApiModels\Store;

class DiscussionController extends Controller
{
    /**
     * @var ApiClient
     */
    private $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function show(Request $request)
    {
        $discussionQuery = app('waterhole.discussionQuery');
        ksort($discussionQuery);

        $postsQuery = app('waterhole.postsQuery');
        $postsQuery['filter[discussion]'] = $request->route('id');
        $postsQuery['sort'] = 'number';
        $perPage = 20;
        $postsQuery['page[limit]'] = $perPage;
        $postsQuery['page[offset]'] = (Paginator::resolveCurrentPage() - 1) * $perPage;
        ksort($postsQuery);

        $res = $this->api->get('discussions/'.$request->route('id').'?'.Arr::query($discussionQuery));
        $store = new Store();
        $discussion = $store->sync(json_decode($res->getBody()));

        $res = $this->api->get('posts?'.Arr::query($postsQuery));
        $posts = $store->sync($doc = json_decode($res->getBody()));
        $paginator = new LengthAwarePaginator($posts, $doc->meta->total, $perPage);
        $paginator->withPath(request()->url());

        $res = $this->api->get('reactionTypes');
        $reactionTypes = $store->sync($doc = json_decode($res->getBody()));

        return view('forum.discussion', [
            'discussion' => $discussion,
            'posts' => $paginator,
            'reactionTypes' => $reactionTypes,
            'store' => $store
        ]);
    }

    public function subscription(string $id)
    {
        $discussion = waterhole_api(
            (new ServerRequest('GET', 'discussions/'.$id))
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
                                'subscribable' => ['data' => $discussion->identifier()]
                            ]
                        ]
                    ])
            );
        } elseif ($discussion->subscription) {
            waterhole_api(new ServerRequest('DELETE', $discussion->subscription->links->self));
        }

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    public function update(string $id)
    {
        waterhole_api(
            (new ServerRequest('PATCH', 'discussions/'.$id))
                ->withParsedBody([
                    'data' => [
                        'type' => 'discussions',
                        'id' => $id,
                    ] + request()->only('attributes', 'relationships')
                ])
        );

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    public function delete(string $id)
    {
        waterhole_api(
            (new ServerRequest('DELETE', 'discussions/'.$id))
        );

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    public function reply(string $id)
    {
        $discussion = waterhole_api(
            (new ServerRequest('GET', 'discussions/'.$id))
                ->withQueryParams(['include' => 'category.ancestors'])
        );

        $post = waterhole_api(
            (new ServerRequest('POST', 'posts'))
                ->withParsedBody([
                    'data' => [
                        'type' => 'posts',
                        'attributes' => [
                            'content' => request('content')
                        ],
                        'relationships' => [
                            'discussion' => ['data' => $discussion->identifier()]
                        ]
                    ]
                ])
        );

        $post->discussion = $discussion;
        $url = post_route($post);

        return request()->expectsJson()
            ? ['redirect' => $url]
            : redirect($url);
    }

    public function markAsRead(string $id)
    {
        $discussion = waterhole_api(
            (new ServerRequest('GET', 'discussions/'.$id))
        );

        waterhole_api(
            (new ServerRequest('POST', 'bookmarks'))
                ->withParsedBody([
                    'data' => [
                        'type' => 'bookmarks',
                        'attributes' => [
                            'lastReadPostNumber' => $discussion->lastCommentNumber
                        ],
                        'relationships' => [
                            'discussion' => ['data' => $discussion->identifier()]
                        ]
                    ]
                ])
        );

        return request()->isXmlHttpRequest()
            ? response()->noContent()
            : redirect()->back();
    }

    protected static $components = ['DiscussionListItem', 'DiscussionControls', 'DiscussionHeader'];

    public function renderComponent(string $id, string $component)
    {
        if (! in_array($component, static::$components)) {
            abort(404);
        }

        $discussion = waterhole_api(
            (new ServerRequest('GET', 'discussions/'.$id))
                ->withQueryParams(app('waterhole.discussionQuery'))
        );

        $class = (new ComponentTagCompiler)->guessClassName($component);

        if (! class_exists($class)) {
            $component = new AnonymousComponent(view('components.'.$component), ['discussion' => $discussion]);
        } else {
            $component = new $class($discussion);
        }

        return $component->render()->with($component->data());
    }
}
