<x-waterhole::layout title="Edit Post">
    <x-waterhole::dialog title="Edit Post" class="post-create">
        <form
            method="POST"
            action="{{ route('waterhole.posts.update', ['post' => $post]) }}"
        >
            @csrf
            @method('PATCH')
            <input type="hidden" name="redirect" value="{{ url()->previous() }}">

            <div class="form">
                <x-waterhole::validation-errors :errors="$errors"/>

                @include('waterhole::posts.fields')

                <div class="toolbar">
                    <button type="submit" class="btn btn--primary">Save</button>
                    <button onclick="window.history.back()" class="btn">Cancel</button>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::layout>
