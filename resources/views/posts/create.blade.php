@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        @if (! $form->model->channel)
            <form
                method="POST"
                action="{{ route('waterhole.posts.store') }}"
                data-controller="draft dirty-form"
                data-action="
                    input->draft#queue
                    change->draft#queue
                    focusout->draft#saveNow
                    turbo:submit-start->draft#submitStart
                    turbo:submit-end->draft#submitEnd
                    draft:saved->dirty-form#markClean
                "
            >
                @csrf

                <x-waterhole::dialog class="measure" :title="$title">
                    <x-waterhole::channel-picker id="channel_id" name="channel_id" show-links />
                </x-waterhole::dialog>
            </form>
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
                                form="post-create-form"
                                show-links
                            />
                        </ui-menu>
                    </ui-popup>
                </x-slot>

                <x-waterhole::form
                    :fields="$form->fields()"
                    :panel-attributes="['class' => 'stack gap-lg']"
                    action="{{ route('waterhole.posts.store') }}"
                    id="post-create-form"
                    class="stacked-fields"
                    data-controller="draft dirty-form"
                    data-action="
                        input->draft#queue
                        change->draft#queue
                        focusout->draft#saveNow
                        turbo:submit-start->draft#submitStart
                        turbo:submit-end->draft#submitEnd
                        draft:saved->dirty-form#markClean
                    "
                >
                    @if (filled($instructions = $form->model->channel->instructions_html))
                        <div class="rounded p-lg bg-warning-soft content">
                            {{ $instructions }}
                        </div>
                    @endif

                    <x-slot:actions>
                        <button
                            class="btn btn--wide bg-accent"
                            name="commit"
                            type="submit"
                            value="1"
                            data-controller="hotkey"
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
                    </x-slot>
                </x-waterhole::form>
            </x-waterhole::dialog>
        @endif
    </div>
</x-waterhole::layout>
