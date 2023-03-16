<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Post;
use Waterhole\Search\Results;
use Waterhole\Search\Searcher;

class PostTitle extends Field
{
    public ?Results $similarPosts = null;

    public function __construct(public ?Post $model)
    {
        if (
            !$model->exists &&
            $model->channel->show_similar_posts &&
            ($title = old('title')) &&
            strlen($title) >= 10
        ) {
            $this->similarPosts = resolve(Searcher::class)->search(
                q: $title,
                limit: 3,
                channelIds: [$model->channel_id],
            );
        }
    }

    public function render(): string
    {
        return <<<'blade'
            @php
                $label = __([
                    "waterhole.channel-{$model->channel->slug}-post-title-label",
                    'waterhole::forum.post-title-label',
                ]);

                $description = __([
                    "waterhole.channel-{$model->channel->slug}-post-title-description",
                    '',
                ]);

                $similarPostsLabel = __([
                    "waterhole.channel-{$model->channel->slug}-similar-posts-label",
                    'waterhole::forum.similar-posts-label',
                ]);
            @endphp

            <div class="stack gap-sm" data-controller="similar-posts">
                <x-waterhole::field name="title" :$label :$description>
                    <input
                        id="{{ $component->id }}"
                        name="title"
                        type="text"
                        value="{{ old('title', $model->title ?? '') }}"
                        data-action="similar-posts#input"
                    >
                </x-waterhole::field>

                @if ($model->channel->show_similar_posts)
                    <button
                        type="submit"
                        hidden
                        data-similar-posts-target="submit"
                        data-turbo-frame="similar-posts"
                    ></button>

                    <turbo-frame id="similar-posts" target="_top" hidden data-similar-posts-target="frame">
                        @if (!empty($similarPosts->hits))
                            <div class="bg-warning-soft p-md rounded stack gap-xs text-xs">
                                <p class="weight-bold">{{ $similarPostsLabel }}</p>
                                @foreach ($similarPosts->hits as $hit)
                                    <p><a href="{{ $hit->post->url }}">{{ $hit->post->title }}</a></p>
                                @endforeach
                            </div>
                        @endif
                    </turbo-frame>
                @endif
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['title' => ['required', 'string', 'max:255']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->title = $request->validated('title');
    }
}
