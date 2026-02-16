<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
            config('waterhole.system.search_engine') &&
            !$model->exists &&
            $model->channel->show_similar_posts &&
            ($title = old('title', $model->title)) &&
            strlen($title) >= 10
        ) {
            $this->similarPosts = resolve(Searcher::class)->search(
                q: $title,
                limit: 3,
                channelIds: [$model->channel_id],
                in: ['title', 'body'],
            );
        }
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="stack gap-sm" @if (!$model->exists) data-controller="similar-posts" @endif>
                <x-waterhole::field
                    name="title"
                    :label="__($model->channel->translations[$key = 'waterhole::forum.post-title-label'] ?? $key)"
                    :description="__($model->channel->translations[$key = 'waterhole::forum.post-title-description'] ?? '')">
                    <input
                        id="{{ $component->id }}"
                        name="title"
                        type="text"
                        value="{{ old('title', $model->title ?? '') }}"
                        data-action="similar-posts#input"
                    >
                </x-waterhole::field>

                @if (!$model->exists && $model->channel->show_similar_posts)
                    <button
                        type="submit"
                        hidden
                        data-similar-posts-target="submit"
                        data-turbo-frame="similar-posts"
                    ></button>

                    <turbo-frame id="similar-posts" target="_top" data-similar-posts-target="frame" @if (empty($similarPosts->hits)) hidden @endif>
                        @if (!empty($similarPosts->hits))
                            <div class="bg-warning-soft p-xs rounded stack">
                                <h2 class="h6 p-sm pb-xxs">
                                    {{ __($model->channel->translations[$key = 'waterhole::forum.similar-posts-label'] ?? $key) }}
                                </h2>
                                @foreach ($similarPosts->hits as $hit)
                                    @php $excerpt = strip_tags((string) $hit->body); @endphp
                                    <div class="block-link p-sm stack gap-xxs overlay-container">
                                        <a href="{{ $hit->post->url }}" class="has-overlay color-text no-underline text-sm">
                                            {{ $hit->post->title }}
                                        </a>
                                        @if ($excerpt)
                                            <span class="text-xxs overflow-ellipsis">
                                                {{ $hit->body }}
                                            </span>
                                        @endif
                                    </div>
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
        $this->model->slug = Str::slug($this->model->title);
    }
}
