@props([
  'id' => '',
  'name' => 'channel_id',
  'value' => null,
  'exclude' => []
])

{{-- TODO: list using nav config --}}
<select id="{{ $id }}" name="{{ $name }}">
  @foreach (Waterhole\Models\Channel::all()->except($exclude) as $channel)
    @can('post', $channel)
      <option value="{{ $channel->id }}" {{ $value == $channel->id ? 'selected' : '' }}>
        {{ $channel->display_name }}
      </option>
    @endcan
  @endforeach
</select>
