@extends('waterhole::layout')

@section('content')
  <div class="has-left-sidebar has-right-sidebar">

    <div class="sidebar">
      <x-waterhole::nav/>
    </div>

    <main>
      <form method="get">
        <x-waterhole::search-input :placeholder="__('waterhole::forum.search-placeholder')"/>
      </form>

      <div class="card home-toolbar">
        <div class="toolbar"> <!-- Extend\HomeToolbar -->
          <h1>Home</h1>
          <!-- Sort selector -->
          <!-- Layout selector -->
          <div class="spacer"></div>
          <a href="{{ route('waterhole.posts.create') }}">New Post</a>
        </div>
      </div>

      <x-waterhole::posts.list :posts="$posts"/>
    </main>

    <aside>
      Right sidebar
    </aside>

  </div>
@endsection
