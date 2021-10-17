<x-waterhole::layout :title="'Comment in '.$post->title">
  <div class="container">
    <div class="post-page stack">
      <h1 hidden>{{ $post->title }}</h1>
      <div>
          <a
              href="{{ $post->url }}"
              class="with-icon"
          >
              <x-waterhole::icon icon="heroicon-s-arrow-sm-left"/>
              <span>Back to all comments</span>
          </a>
      </div>

      <x-waterhole::comment-full :comment="$comment" with-replies/>
    </div>
  </div>
</x-waterhole::layout>
