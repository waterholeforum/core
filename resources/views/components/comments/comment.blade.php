@props(['comment', 'depth' => 0])

<article id="comment-{{ $comment->id }}">
  <h3>{{ $comment->user->name }}</h3>

  @if ($comment->parent && ! $depth)
    <div><a href="{{ $comment->parent->url }}">In reply to {{ $comment->parent->user->name }}</a></div>
  @endif

  {{ $comment->body }}

  <x-waterhole::actions actionable="comments" :items="[$comment]"/>

  @if ($comment->relationLoaded('replies'))
    @foreach ($comment->replies as $child)
      <x-waterhole::comments.comment :comment="$child" :depth="$depth + 1"/>
    @endforeach
  @endif
</article>
