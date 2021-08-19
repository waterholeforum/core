@props(['post', 'parent' => null])

@can('reply', $post)
  <form action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}" method="POST" id="reply">
    @csrf

    @if ($parent)
      <input type="hidden" name="parent_id" value="{{ $parent->id }}">
      <p>In reply to {{ $parent->user->name }}</p>
    @endif

    <x-waterhole::errors :errors="$errors"/>

    <textarea name="body">{{ old('body') }}</textarea>

    <button type="submit">Post</button>
  </form>
@else
  <a href="{{ route('waterhole.login') }}">Log in to reply</a>
@endcan
