<x-waterhole::layout title="New Post">
    <div class="dialog post-create">
        <header class="dialog__header">
            <h1 class="dialog__title">New Post</h1>
        </header>

        <div class="dialog__body">
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
        </div>
    </div>
</x-waterhole::layout>
