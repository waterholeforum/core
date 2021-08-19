<x-waterhole::layout title="Edit Post">
  <form method="POST" action="{{ route('waterhole.posts.update', ['post' => $post]) }}">
    @csrf
    @method('PATCH')

    <x-waterhole::errors :errors="$errors"/>

    @include('waterhole::posts.fields')

    <div>
      <a href="{{ $post->url }}">Cancel</a>
      <button type="submit">Save</button>
    </div>
  </form>
</x-waterhole::layout>
