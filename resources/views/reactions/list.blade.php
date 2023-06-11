@php
    $query = $model->reactions()->whereBelongsTo($reactionType);
    $count = $query->count();
    $reactions = $query
        ->with('user')
        ->latest()
        ->take(20)
        ->get();
@endphp

<turbo-frame id="reactions">
    <ul role="list">
        @foreach ($reactions as $reaction)
            <li>{{ Waterhole\username($reaction->user) }}</li>
        @endforeach

        @if ($count > 20)
            <li>
                {{ __('waterhole::system.user-list-overflow', ['count' => $count - 20]) }}
            </li>
        @endif
    </ul>
</turbo-frame>
