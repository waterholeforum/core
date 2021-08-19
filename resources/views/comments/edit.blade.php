<x-waterhole::layout title="Edit Comment">
  <form method="POST" action="{{ route('waterhole.comments.update', ['comment' => $comment]) }}">
    @csrf
    @method('PATCH')

    <x-waterhole::errors :errors="$errors"/>

    <textarea name="body">{{ old('body', $comment->body) }}</textarea>

    <div>
      <a href="{{ $comment->url }}">Cancel</a>
      <button type="submit">Save</button>
    </div>
  </form>
</x-waterhole::layout>
