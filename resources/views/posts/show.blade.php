<x-waterhole::layout :title="$post->title">
    <div class="container">
      <div>
          <a href="{{ $post->channel->url }}">{{ $post->channel->display_name }}</a>
      </div>

        <x-waterhole::actions actionable="posts" :items="[$post]"/>

        <h1>{{ $post->title }}</h1>

        {{ $post->body }}

        <h2>{{ $post->comment_count }} comments</h2>

        <details>
            <summary>Sort by {{ $currentSort->name() }}</summary>
            @foreach ($sorts as $sort)
                <a href="{{ $post->url }}?sort={{ $sort->handle() }}">{{ $sort->name() }}</a>
            @endforeach
        </details>

        @if ($comment)
            <p><a href="{{ request()->fullUrlWithQuery(['comment' => null]) }}">View
                    all comments</a></p>

            <x-waterhole::comments.comment :comment="$comment"/>
        @else
            @foreach ($comments as $comment)
                <x-waterhole::comments.comment :comment="$comment"/>
            @endforeach

            {{ $comments->links() }}
        @endif

        <x-waterhole::comments.reply :post="$post"/>
    </div>
</x-waterhole::layout>
