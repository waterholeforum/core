<x-waterhole::layout title="Edit Comment">
    <turbo-frame id="@domid($comment)">
        <form
            method="POST"
            action="{{ route('waterhole.comments.update', ['comment' => $comment]) }}"
        >
            @csrf
            @method('PATCH')
            <input type="hidden" name="return" value="{{ old('return', url()->previous($comment->url)) }}">

            <x-waterhole::validation-errors :errors="$errors"/>

            <textarea name="body">{{ old('body', $comment->body) }}</textarea>

            <div>
                <a href="{{ url()->previous($comment->url) }}">Cancel</a>
                <button type="submit">Save</button>
            </div>
        </form>
    </turbo-frame>
</x-waterhole::layout>
