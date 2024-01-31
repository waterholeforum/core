<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\ReactionSet;

class ChannelReactions extends Field
{
    public function __construct(public ?Channel $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label with-icon">
                    @icon('tabler-mood-smile', ['class' => 'text-md'])
                    {{ __('waterhole::cp.channel-reactions-label') }}
                </div>

                <div class="stack gap-md">
                    <x-waterhole::field
                        name="posts_reaction_set_id"
                        :label="__('waterhole::cp.channel-reactions-posts-label')"
                        class="grow color-muted align-center"
                    >
                        @php $id = $component->id @endphp
                        <x-waterhole::reaction-set-picker
                            :id="$id"
                            name="posts_reaction_set_id"
                            :value="old('posts_reaction_set_id', $model->posts_reaction_set_id)"
                            :default="Waterhole\Models\ReactionSet::defaultPosts()"
                        />
                    </x-waterhole::field>

                    <x-waterhole::field
                        name="comments_reaction_set_id"
                        :label="__('waterhole::cp.channel-reactions-comments-label')"
                        class="grow color-muted align-center"
                    >
                        @php $id = $component->id @endphp
                        <x-waterhole::reaction-set-picker
                            :id="$id"
                            name="comments_reaction_set_id"
                            :value="old('comments_reaction_set_id', $model->comments_reaction_set_id)"
                            :default="Waterhole\Models\ReactionSet::defaultComments()"
                        />
                    </x-waterhole::field>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'posts_reaction_set_id' => ['nullable', new Exists(ReactionSet::class, 'id')],
            'comments_reaction_set_id' => ['nullable', new Exists(ReactionSet::class, 'id')],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->posts_reaction_set_id = $request->validated('posts_reaction_set_id');
        $this->model->comments_reaction_set_id = $request->validated('comments_reaction_set_id');
    }
}
