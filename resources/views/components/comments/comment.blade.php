@props(['comment', 'depth' => 0])

<li>
  {{ $comment->body }}

  @if ($depth < config('waterhole.forum.comment_depth', 1))
    <details>
      <summary>Reply</summary>
      <x-waterhole::comments.reply :post="$comment->post" :comment="$comment"/>
    </details>
  @endif

  <details>
    <summary>Actions</summary>
    <a href="{{ route('waterhole.comments.edit', ['comment' => $comment]) }}">Edit</a>
    <x-waterhole::actions actionable="comments" :items="[$comment]"/>
  </details>

  @if (count($comment->children))
    <ol>
      @foreach ($comment->children as $child)
        <x-waterhole::comments.comment :comment="$child" :depth="$depth + 1"/>
      @endforeach
    </ol>
  @endif
</li>
