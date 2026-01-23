@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section measure">
        <form method="POST" action="{{ route('waterhole.posts.store') }}">
            @csrf

            @if (! $form->model->channel)
                <x-waterhole::dialog class="measure" :title="$title">
                    <x-waterhole::channel-picker id="channel_id" name="channel_id" show-links />
                </x-waterhole::dialog>
            @else
                <x-waterhole::dialog :title="$title">
                    <x-slot name="header">
                        <ui-popup placement="bottom-start">
                            <button class="btn" type="button">
                                <x-waterhole::channel-badge :channel="$form->model->channel" />
                                @icon('tabler-selector')
                            </button>

                            <ui-menu class="menu menu--lg" hidden>
                                <x-waterhole::channel-picker
                                    id="channel_id"
                                    name="channel_id"
                                    :value="$form->model->channel_id"
                                    show-links
                                />
                            </ui-menu>
                        </ui-popup>
                    </x-slot>

                    <div class="stack gap-xl stacked-fields">
                        <x-waterhole::validation-errors />

                        @if ($instructions = $form->model->channel->instructions_html)
                            <div class="rounded p-lg bg-warning-soft content">
                                {{ $instructions }}
                            </div>
                        @endif

                        @components($form->fields())

                        <div>
                            <button
                                class="btn btn--wide bg-accent"
                                name="commit"
                                type="submit"
                                value="1"
                                data-hotkey="Mod+Enter"
                                data-hotkey-scope="post-body"
                            >
                                {{ __('waterhole::forum.post-submit-button') }}
                            </button>
                        </div>
                    </div>
                </x-waterhole::dialog>
            @endif
        </form>
    </div>
</x-waterhole::layout>
