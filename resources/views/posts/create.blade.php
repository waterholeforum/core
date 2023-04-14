@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section measure">
        <form
            method="POST"
            action="{{ route('waterhole.posts.store') }}"
        >
            @csrf

            @if (!$form->model->channel)
                <x-waterhole::dialog class="measure" :title="$title">
                    <x-waterhole::channel-picker
                        id="channel_id"
                        name="channel_id"
                    />
                </x-waterhole::dialog>
            @else
                <x-waterhole::dialog :title="$title">
                    <x-slot:header>
                        <ui-popup placement="bottom-start">
                            <button class="btn" type="button">
                                <x-waterhole::channel-label :channel="$form->model->channel"/>
                                @icon('tabler-selector')
                            </button>

                            <ui-menu class="menu measure" hidden>
                                <x-waterhole::channel-picker
                                    id="channel_id"
                                    name="channel_id"
                                    :value="$form->model->channel_id"
                                />
                            </ui-menu>
                        </ui-popup>
                    </x-slot:header>

                    <div class="stack gap-xl stacked-fields">
                        <x-waterhole::validation-errors/>

                        @if ($instructions = $form->model->channel->instructions_html)
                            <div class="rounded p-lg bg-warning-soft content">
                                {{ Waterhole\emojify($instructions) }}
                            </div>
                        @endif

                        @components($form->fields())

                        <div>
                            <button
                                class="btn btn--wide bg-accent"
                                name="commit"
                                type="submit"
                                value="1"
                                data-hotkey="Meta+Enter,Ctrl+Enter"
                                data-hotkey-scope="post-body"
                            >{{ __('waterhole::forum.post-submit-button') }}</button>
                        </div>
                    </div>
                </x-waterhole::dialog>
            @endif

        </form>
    </div>
</x-waterhole::layout>
