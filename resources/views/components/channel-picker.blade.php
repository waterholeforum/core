@props([
    'name' => null,
    'value' => null,
    'exclude' => [],
    'allowNull' => false,
])

<div {{ $attributes }} data-controller="channel-picker" class="field">
    {{-- TODO: list using nav config --}}
    <select name="{{ $name }}" class="input" data-action="channel-picker#update">
        @if ($allowNull)
            <option value="">Select a Channel</option>
        @endif

        @foreach (Waterhole\Models\Channel::all()->except($exclude) as $channel)
            @can('post', $channel)
                <option
                    value="{{ $channel->id }}"
                    {{ $value == $channel->id ? 'selected' : '' }}
                    title="{{ $channel->description }}"
                    data-instructions="{{ $channel->instructions }}"
                >
                    {{ $channel->display_name }}
                </option>
            @endcan
        @endforeach
    </select>

    <div
        class="alert alert--info"
        hidden
        data-channel-picker-target="instructions"
    >
        <div class="content" data-channel-picker-target="instructionsContent"></div>
    </div>
</div>
