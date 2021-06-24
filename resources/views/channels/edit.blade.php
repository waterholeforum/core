@extends('waterhole::layout')

@section('title', 'Edit Channel')

@section('content')
  <form method="POST" action="{{ route('waterhole.channels.update', ['channel' => $channel]) }}">
    @csrf
    @method('PATCH')

    <!-- TODO: componentize -->

    @include('waterhole::channels.fields')

    <div>
      <a href="{{ $channel->url }}">Cancel</a>
      <button type="submit">Save</button>
    </div>
  </form>
@endsection
