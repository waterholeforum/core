@props(['post', 'interactive'])

@if ($interactive)
<turbo-frame id="@domid($post)-likes">
    <x-waterhole::action-button
        :for="$post"
        :action="Waterhole\Actions\React::class"
        class="btn btn--transparent btn--small"
    />
</turbo-frame>
@else

<span class="metric metric--score metric--{{ $post->score }}">
    <x-heroicon-o-thumb-up class="icon"/>
    {{ $post->score }}
</span>

@endif
