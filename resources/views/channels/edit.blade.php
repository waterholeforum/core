<x-waterhole::layout title="Edit Channel">
  <form method="POST" action="{{ route('waterhole.channels.update', ['channel' => $channel]) }}">
    @csrf
    @method('PATCH')

    <x-waterhole::validation-errors :errors="$errors"/>

    <!-- TODO: componentize -->

    @include('waterhole::channels.fields')

    <div>
      <a href="{{ $channel->url }}">Cancel</a>
      <button type="submit">Save</button>
    </div>
  </form>
</x-waterhole::layout>
