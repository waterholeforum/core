@extends('waterhole::layout')

@section('title', 'Create a Post')

@section('content')
  <form method="POST" action="{{ route('waterhole.posts.store') }}">
    @csrf

    <!-- TODO: components -->

    @include('waterhole::posts.fields')

    <div>
      <button type="submit">Post</button>
    </div>
  </form>
@endsection
