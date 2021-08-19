<x-waterhole::layout title="Create a Post">
  <form method="POST" action="{{ route('waterhole.posts.store') }}">
    @csrf

    <x-waterhole::errors :errors="$errors"/>

    <!-- TODO: components -->

    <div>
      <label for="channel">Channel</label>
      <x-waterhole::channel-picker id="channel" name="channel_id" :value="old('channel_id', $post->channel_id ?? request('channel'))"/>
    </div>

    @include('waterhole::posts.fields')

    <div>
      <button type="submit">Post</button>
    </div>
  </form>
</x-waterhole::layout>
