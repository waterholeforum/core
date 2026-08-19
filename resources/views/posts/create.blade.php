@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::forum-layout :title="$title">
    <div class="container section">
        @if (! $form->model->channel)
            <x-waterhole::dialog class="measure" :title="$title">
                <form
                    action="{{ route('waterhole.posts.create') }}"
                    method="get"
                >
                    <x-waterhole::channel-picker
                        id="channel_id"
                        name="channel_id"
                        show-links
                    />
                </form>
            </x-waterhole::dialog>
        @else
            <x-waterhole::dialog class="measure" :title="$title">
                <x-slot:header>
                    <x-waterhole::channel-select
                        name="channel_id"
                        :channel="$form->model->channel"
                        form="post-create-form"
                        show-links
                    />
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
                            data-shortcut-trigger="form.submit"
                        >
                            {{ __('waterhole::forum.post-submit-button') }}

                            <ui-tooltip>
                                {{ __('waterhole::forum.post-submit-button') }}
                                <x-waterhole::shortcut-label
                                    shortcut="form.submit"
                                />
                            </ui-tooltip>
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
</x-waterhole::forum-layout>
