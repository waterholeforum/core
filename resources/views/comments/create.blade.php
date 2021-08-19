<x-waterhole::layout title="Post Comment">
  <h1>Reply to {{ $post->title }}</h1>

  <x-waterhole::comments.reply :post="$post" :parent="$parent"/>
</x-waterhole::layout>
