<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Http\Controllers;

use Waterhole\Exceptions\WaterholeApiException;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Nyholm\Psr7\ServerRequest;

class NewDiscussionController extends Controller
{
    public function show()
    {
        if ($slug = request('category')) {
            $category = waterhole_api(
                (new ServerRequest('GET', 'categories'))
                    ->withQueryParams([
                        'filter' => ['slug' => $slug],
                        'include' => 'ancestors'
                    ])
            )[0];
        }

        return view('forum.new-discussion')->with([
            'category' => $category ?? null
        ]);
    }

    public function post()
    {
        $data = request()->only('attributes', 'relationships');

        if ($categoryId = $data['relationships']['category']['data']['id'] ?? null) {
            $category = waterhole_api(
                (new ServerRequest('GET', 'categories/'.$categoryId))
            );
        } else {
            unset($data['relationships']['category']);
        }

        if (request()->has('publish')) {
            try {
                $discussion = waterhole_api(
                    (new ServerRequest('POST', 'discussions'))
                        ->withQueryParams(app('waterhole.discussionQuery'))
                        ->withParsedBody([
                            'data' => [
                                'type' => 'discussions'
                            ] + $data
                        ])
                );

                return redirect(discussion_route($discussion));
            } catch (WaterholeApiException $e) {
                if ($e->response->getStatusCode() !== 422) {
                    throw $e;
                }

                $errors = new MessageBag(
                    collect($e->response->getPayload()->jsonSerialize()->errors)
                        ->mapToGroups(function ($item) {
                            if (Str::startsWith($item->source->pointer, '/data/')) {
                                $field = str_replace('/', '.', substr($item->source->pointer, 6));
                                return [$field => $item->detail];
                            }
                        })
                        ->all()
                );
            }
        }

        return redirect()
            ->route('discussion.new', ['category' => $category->slug ?? null])
            ->withInput()
            ->withErrors($errors ?? []);
    }
}
