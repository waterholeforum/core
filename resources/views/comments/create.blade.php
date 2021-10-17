<x-waterhole::layout title="Post Comment">
  <h1>Reply to {{ $post->title }}</h1>

  <turbo-frame id="reply-composer">
    <x-waterhole::comment-reply-composer :post="$post" :parent="$parent"/>
  </turbo-frame>
</x-waterhole::layout>
