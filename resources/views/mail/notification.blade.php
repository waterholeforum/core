<x-mail::message>
{{ $title }}

<x-mail::panel>
<p>
@isset($avatar)
<img src="{{ $avatar }}" width="32" height="32" style="border-radius: 100%; vertical-align: middle">
@endisset
<strong>{{ $name }}:</strong>
</p>

{!! preg_replace('/<script.*?\/script>/s', '', $excerpt) !!}
</x-mail::panel>

@isset($button)
<x-mail::button :url="$url" color="primary">
{{ $button }}
</x-mail::button>
@endisset

<x-slot:subcopy>
{{ $reason }}<br>

<x-mail::link :url="$unsubscribeUrl">{{ $unsubscribeText }}</x-mail::link> &nbsp;
<x-mail::link :url="route('waterhole.preferences.notifications')">{{ __('waterhole::notifications.manage-notification-preferences-link') }}</x-mail::link>
</x-slot:subcopy>
</x-mail::message>
