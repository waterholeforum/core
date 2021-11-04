<x-waterhole::layout title="New Post">
    <x-waterhole::dialog
        class="post-create"
        title="New Post"
    >
        <form method="POST" action="{{ route('waterhole.posts.store') }}">
            @csrf

            <div class="form">
                <x-waterhole::validation-errors :errors="$errors"/>

                <x-waterhole::field
                    name="channel_id"
                    label="Channel"
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
                    <button type="submit" class="btn btn--primary" name="publish">Post</button>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::layout>
