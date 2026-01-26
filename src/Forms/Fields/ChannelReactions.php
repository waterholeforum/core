<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\View\Components\ReactionSetPicker;

class ChannelReactions extends Field
{
    public function __construct(public ?Channel $model) {}

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
                        name="posts_reaction_set"
                        :label="__('waterhole::cp.channel-reactions-posts-label')"
                        class="grow color-muted align-center"
                    >
                        @php $id = $component->id @endphp
                        <x-waterhole::reaction-set-picker
                            :id="$id"
                            name="posts_reaction_set"
                            :value="old('posts_reaction_set')"
                            :default="Waterhole\Models\ReactionSet::defaultPosts()"
                            :enabled="$model->posts_reactions_enabled"
                            :selected-id="$model->posts_reaction_set_id"
                        />
                    </x-waterhole::field>

                    <x-waterhole::field
                        name="comments_reaction_set"
                        :label="__('waterhole::cp.channel-reactions-comments-label')"
                        class="grow color-muted align-center"
                    >
                        @php $id = $component->id @endphp
                        <x-waterhole::reaction-set-picker
                            :id="$id"
                            name="comments_reaction_set"
                            :value="old('comments_reaction_set')"
                            :default="Waterhole\Models\ReactionSet::defaultComments()"
                            :enabled="$model->comments_reactions_enabled"
                            :selected-id="$model->comments_reaction_set_id"
                        />
                    </x-waterhole::field>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->appendRules([
            'posts_reaction_set' => ['nullable', ReactionSetPicker::rule()],
            'comments_reaction_set' => ['nullable', ReactionSetPicker::rule()],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $postsValue = $request->validated('posts_reaction_set');
        $commentsValue = $request->validated('comments_reaction_set');

        [$postsEnabled, $postsSetId] = ReactionSetPicker::resolveSelection($postsValue);
        $this->model->posts_reactions_enabled = $postsEnabled;
        $this->model->posts_reaction_set_id = $postsSetId;

        [$commentsEnabled, $commentsSetId] = ReactionSetPicker::resolveSelection($commentsValue);
        $this->model->comments_reactions_enabled = $commentsEnabled;
        $this->model->comments_reaction_set_id = $commentsSetId;
    }
}
