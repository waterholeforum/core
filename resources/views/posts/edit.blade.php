@php
    $title = __('waterhole::forum.edit-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <x-waterhole::dialog class="measure" :title="$title">
            <x-waterhole::form
                :fields="$form->fields()"
                method="PATCH"
                :cancel-url="$post->url"
                :submit-attributes="[
                    'data-controller' => 'hotkey',
                    'data-hotkey' => 'Mod+Enter',
                    'data-hotkey-scope' => 'post-body',
                ]"
                action="{{ route('waterhole.posts.update', ['post' => $post]) }}"
                class="stacked-fields"
                :panel-attributes="['class' => 'stack gap-lg']"
                data-controller="dirty-form"
            />
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
