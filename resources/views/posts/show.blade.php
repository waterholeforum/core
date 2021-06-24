@extends('waterhole::layout')

@section('title', $post->title)

@section('content')
  <div>
    <a href="{{ $post->channel->url }}">{{ $post->channel->display_name }}</a>
  </div>

  <div>
    <a href="{{ $post->edit_url }}">Edit</a>
  </div>

  <x-waterhole::actions actionable="posts" :items="[$post]"/>

  <h1>{{ $post->title }}</h1>

  {{ $post->body }}

  <h2>{{ $post->comment_count }} comments</h2>

  <ol>
    @foreach ($post->comments()->whereNull('parent_id')->with('children')->get() as $comment)
      <x-waterhole::comments.comment :comment="$comment"/>
    @endforeach
  </ol>

  <x-waterhole::comments.reply :post="$post"/>
@endsection
