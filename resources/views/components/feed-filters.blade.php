@php
    $filters = $feed->filters();
@endphp

<div {{ $attributes->class('tabs') }}>
    @foreach ($filters->slice(0, $limit) as $filter)
        <a
            href="{{ $url($filter) }}"
            class="tab"
            @if ($feed->currentFilter() === $filter) aria-current="page" @endif
        >{{ $filter->label() }}</a>
    @endforeach

    @if ($filters->count() > $limit)
        <x-waterhole::selector
            :value="$feed->currentFilter()"
            :options="$filters->slice($limit)->all()"
            :label="fn($filter) => $filter->label()"
            :href="$url"
            button-class="tab"
            placement="bottom-start"
        >
            <x-slot name="button">
                @icon('tabler-dots', ['aria-label' => __('waterhole::system.more-button')])
            </x-slot>
        </x-waterhole::selector>
    @endif
</div>
