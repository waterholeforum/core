@extends('waterhole::centered')

@section('content')
  <form action="{{ route('waterhole.action') }}" method="POST">
    @csrf

    <input type="hidden" name="action" value="{{ get_class($action) }}">
    <input type="hidden" name="actionable" value="{{ $actionable }}">
    <input type="hidden" name="redirect" value="{{ old('redirect', url()->previous()) }}">

    @foreach ($items as $item)
      <input type="hidden" name="id[]" value="{{ $item->id }}">
    @endforeach

    <x-waterhole::errors :errors="$errors"/>

    {{ $confirmation }}

    <a href="{{ old('redirect', url()->previous()) }}">Cancel</a>
    <button type="submit" name="confirmed" value="1">{{ $action->buttonText($items) }}</button>
  </form>
@endsection
