@extends('waterhole::layout')

@section('title', 'Edit Post')

@section('content')
  <form method="POST" action="{{ route('waterhole.posts.update', ['post' => $post]) }}">
    @csrf
    @method('PATCH')

    @include('waterhole::posts.fields')

    <div>
      <a href="{{ $post->url }}">Cancel</a>
      <button type="submit">Save</button>
    </div>
  </form>
@endsection
