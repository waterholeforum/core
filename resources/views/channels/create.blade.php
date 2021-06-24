@extends('waterhole::layout')

@section('title', 'Create a Channel')

@section('content')
  <form method="POST" action="{{ route('waterhole.channels.store') }}">
    @csrf

    <!-- TODO: componentize -->

    @include('waterhole::channels.fields')

    <div>
      <button type="submit">Create</button>
    </div>
  </form>
@endsection
