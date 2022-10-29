<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

class PostChannel extends Field
{
    public ?Channel $channel;

    public function __construct(public Post $model)
    {
        if ($channelId = request('channel')) {
            $this->channel = Channel::findOrFail($channelId);
        } else {
            $this->channel = null;
        }
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="channel_id"
                :label="__('waterhole::forum.post-channel-label')"
            >
                <x-waterhole::channel-picker
                    id="channel_id"
                    name="channel_id"
                    :value="old('channel_id', $channel?->id)"
                    allow-null
                />
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['channel_id' => ['required', Rule::exists(Channel::class, 'id')]]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->channel_id = $request->validated('channel_id');

        Gate::authorize('channel.post', Channel::findOrFail($this->model->channel_id));
    }
}
