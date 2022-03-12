@php
    $title = __('waterhole::forum.create-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <x-waterhole::dialog :title="$title" class="post-editor">
            <form method="POST" action="{{ route('waterhole.posts.store') }}">
                @csrf

                <div class="form">
                    <x-waterhole::validation-errors/>

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

                    @include('waterhole::posts.fields')

                    <div>
                        <button
                            class="btn bg-accent"
                            name="publish"
                            type="submit"
                            value="1"
                        >{{ __('waterhole::forum.post-submit-button') }}</button>
                    </div>
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
