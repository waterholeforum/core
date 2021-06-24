@props(['posts'])

<ol>
  @foreach ($posts as $post)
    <li>
      <a href="{{ $post->url }}">{{ $post->title }}</a>
    </li>
  @endforeach
</ol>

{{ $posts->links() }}
