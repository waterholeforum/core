@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <x-waterhole::dialog :title="$title" class="post-editor">
            <form method="POST" action="{{ route('waterhole.posts.store') }}">
                @csrf

                <div class="form stacked-fields">
                    <x-waterhole::validation-errors/>

                    @components($form->fields())

                    <button
                        class="btn bg-accent"
                        name="commit"
                        type="submit"
                        value="1"
                    >{{ __('waterhole::forum.post-submit-button') }}</button>
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
