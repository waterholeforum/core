<x-waterhole::layout title="Post Comment">
  <h1>Reply to {{ $post->title }}</h1>

  <x-waterhole::comment-reply-composer :post="$post" :parent="$parent"/>
</x-waterhole::layout>
