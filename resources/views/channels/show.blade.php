@extends('waterhole::layout')

@section('title', $channel->name)

@section('content')
  <div class="has-left-sidebar has-right-sidebar">

    <div class="sidebar">
      <x-waterhole::nav/>
    </div>

    <main>
      <form method="get">
        <x-waterhole::search-input :placeholder="__('waterhole::forum.search-placeholder')"/>
      </form>

      <div class="card channel-card">
        <h1>{{ $channel->display_name }}</h1>
        <!-- Sort selector -->
        <!-- Layout selector -->
        <div class="spacer"></div>
        <div>
          @can('update', $channel)
            <a href="{{ route('waterhole.channels.edit', ['channel' => $channel]) }}">Edit Channel</a>
          @endcan

          <x-waterhole::actions actionable="channels" :items="[$channel]"/>

        </div>
        <a href="{{ route('waterhole.posts.create', ['channel' => $channel->id]) }}">New Post</a>
      </div>

      <x-waterhole::posts.list :posts="$posts"/>
    </main>

    <aside>
      Right sidebar
    </aside>

  </div>
@endsection
