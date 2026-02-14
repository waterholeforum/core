@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <form
            method="POST"
            action="{{ route('waterhole.posts.store') }}"
            data-controller="draft"
            data-action="
                input->draft#queue
                change->draft#queue
                focusout->draft#saveNow
                turbo:submit-start->draft#submitStart
                turbo:submit-end->draft#submitEnd
            "
        >
            @csrf

            {{-- Hidden submit button to handle Enter key --}}
            <button name="commit" type="submit" value="1" hidden></button>

            @if (! $form->model->channel)
                <x-waterhole::dialog class="measure" :title="$title">
                    <x-waterhole::channel-picker id="channel_id" name="channel_id" show-links />
                </x-waterhole::dialog>
            @else
                <x-waterhole::dialog class="measure" :title="$title">
                    <x-slot name="header">
                        <ui-popup placement="bottom-start">
                            <button class="btn" type="button">
                                <x-waterhole::channel-label :channel="$form->model->channel" />
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

                        @if (filled($instructions = $form->model->channel->instructions_html))
                            <div class="rounded p-lg bg-warning-soft content">
                                {{ $instructions }}
                            </div>
                        @endif

                        @components($form->fields())

                        <div class="row gap-xs wrap">
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

                            <x-waterhole::draft-controls
                                :saved="(bool) $draft"
                                :action="route('waterhole.draft')"
                                class="push-end"
                            />
                        </div>
                    </div>
                </x-waterhole::dialog>
            @endif
        </form>
    </div>
</x-waterhole::layout>
