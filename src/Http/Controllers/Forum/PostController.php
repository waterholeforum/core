<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->only('create', 'store');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return view('waterhole::posts.show', ['post' => $post]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return view('waterhole::posts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate(Post::rules());

        $post = Post::create(array_merge($validated, [
            'user_id' => Auth::id(),
        ]));

        return redirect($post->url);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('waterhole::posts.edit', ['post' => $post]);
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        $validated = $request->validate(Post::rules());

        $post->update($validated);

        return redirect($post->url);
    }

    public function delete(Post $post)
    {
        $this->authorize('delete', $post);

        return view('waterhole::posts.delete', ['post' => $post]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect($post->channel->url);
    }
}
