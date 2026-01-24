<x-mail::message>
{{ $title }}

<x-mail::panel>
@if (!empty($name))
<p>
@if (!empty($avatar))
<img src="{{ $avatar }}" width="32" height="32" style="border-radius: 100%; vertical-align: middle">
@endif
<strong>{{ $name }}:</strong>
</p>
@endif

@if ($excerpt instanceof Illuminate\Contracts\Support\Htmlable)
{!! preg_replace('/<script.*?\/script>/s', '', $excerpt) !!}
@else
{{ $excerpt }}
@endif
</x-mail::panel>

@isset($button)
<x-mail::button :url="$url" color="primary">
{{ $button }}
</x-mail::button>
@endisset

<x-slot:subcopy>
<p>
{{ $reason }}<br>

<x-mail::link :url="$unsubscribeUrl">{{ $unsubscribeText }}</x-mail::link> &nbsp;
<x-mail::link :url="route('waterhole.preferences.notifications')">{{ __('waterhole::notifications.manage-notification-preferences-link') }}</x-mail::link>
</p>
</x-slot:subcopy>
</x-mail::message>
