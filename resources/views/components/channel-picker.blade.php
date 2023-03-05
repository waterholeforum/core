<div {{ $attributes->class('channel-picker') }}>
    @foreach ($items as $item)
        @if ($item instanceof Waterhole\Models\StructureHeading)
            <h4 class="menu-heading">{{ $item->name }}</h4>
        @elseif ($item instanceof Waterhole\Models\Channel)
            <x-waterhole::menu-item
                type="submit"
                :name="$name"
                :value="$item->id"
                :active="$item->id == $value"
                :label="$item->name"
                :description="new Illuminate\Support\HtmlString(strip_tags($item->description_html))"
                :icon="$item->icon"
                role=""
            />
        @endif
    @endforeach

    @if ($value)
        <input type="hidden" name="channel_id" value="{{ $value }}">
    @endif
</div>
