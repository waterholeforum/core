@props(['feed'])

@php $posts = $feed->posts() @endphp

<ol>
  @foreach ($posts as $post)
    <li>
      <a href="{{ $post->url }}">{{ $post->title }}</a>
      @if ($post->userState)
        Last read: {{ $post->userState->last_read_at }}
      @endif
    </li>
  @endforeach
</ol>

{{ $posts->links() }}
