<x-waterhole::layout title="Create a Channel">
  <form method="POST" action="{{ route('waterhole.channels.store') }}">
    @csrf

    <x-waterhole::validation-errors :errors="$errors"/>

    <!-- TODO: componentize -->

    @include('waterhole::channels.fields', ['channel' => null])

    <div>
      <button type="submit">Create</button>
    </div>
  </form>
</x-waterhole::layout>
