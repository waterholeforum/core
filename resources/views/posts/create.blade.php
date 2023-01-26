@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <x-waterhole::dialog :title="$title" class="measure">
            <form method="POST" action="{{ route('waterhole.posts.store') }}">
                @csrf

                <div class="stack gap-xl stacked-fields">
                    <x-waterhole::validation-errors/>

                    @components($form->fields())

                    @if ($form->model->channel)
                        <div>
                            <button
                                class="btn btn--wide bg-accent"
                                name="commit"
                                type="submit"
                                value="1"
                            >{{ __('waterhole::forum.post-submit-button') }}</button>
                        </div>
                    @endif
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
