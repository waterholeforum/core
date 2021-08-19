<x-waterhole::layout :title="$channel->name">
  <x-waterhole::index>

    <div class="card channel-card">
      <h1>{{ $channel->display_name }}</h1>

      <x-waterhole::feed.sort :feed="$feed"/>

      <div class="spacer"></div>
      <div>
        <x-waterhole::actions actionable="channels" :items="[$channel]"/>
      </div>

      @can('create', Waterhole\Models\Post::class)
        @can('post', $channel)
          <a href="{{ route('waterhole.posts.create', ['channel' => $channel->id]) }}">New Post</a>
        @endcan
      @endcan
    </div>

    <x-waterhole::feed.list :feed="$feed"/>

  </x-waterhole::index>
</x-waterhole::layout>
