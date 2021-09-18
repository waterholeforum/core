<x-waterhole::layout title="New Post">
    <div class="dialog post-create">
        <header class="dialog__header">
            <h1 class="dialog__title">New Post</h1>
        </header>

        <div class="dialog__body">
            <form method="POST" action="{{ route('waterhole.posts.store') }}">
                @csrf

                <x-waterhole::errors :errors="$errors"/>

                <div class="form">
                    <div class="field">
                        <label for="channel" class="field__label">Channel</label>
                        <x-waterhole::channel-picker
                            id="channel"
                            name="channel_id"
                            :value="old('channel_id', $post->channel_id ?? request('channel'))"
                        />

                        <div class="content" style="background: var(--color-fill); border-radius: var(--border-radius); padding: 1.5rem; font-size: .9rem">
                            <p>The community is here to help you with specific coding, algorithm, or language problems. Avoid asking opinion-based questions.</p>
                            <ul>
                                <li>Include details about your goal
                                <li>Describe expected and actual results
                                <li>Include any error messages
                            </ul>
                        </div>
                    </div>

                    @include('waterhole::posts.fields')

                    <div>
                        <button type="submit" class="btn btn--primary">Post</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-waterhole::layout>
