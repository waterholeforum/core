@props(['post', 'comment' => null])

<form action="{{ route('waterhole.posts.comments.store', ['post' => $post]) }}" method="POST">
  @csrf

  @if ($comment)
    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
  @endif

  <x-waterhole::errors :errors="$errors"/>

  <textarea name="body">{{ old('body') }}</textarea>
  <button type="submit">Post</button>
</form>
