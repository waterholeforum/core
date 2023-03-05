@component('mail::message')
{{ $html }}

@component('mail::panel')
<p>
@isset($avatar)
<img src="{{ $avatar }}" width="32" height="32" style="border-radius: 100%; vertical-align: middle">
@endisset
<strong>{{ $name }}:</strong>
</p>

{!! preg_replace('/<script.*?\/script>/s', '', $excerpt) !!}
@endcomponent


@isset($button)
@component('mail::button', ['url' => $url, 'color' => 'primary'])
{{ $button }}
@endcomponent
@endisset

@slot('subcopy')
{{ $reason }}<br>
[{{ $unsubscribeText }}]({{ $unsubscribeUrl }}) &nbsp;
[{{ __('waterhole::notifications.manage-notification-preferences-link') }}]({{ route('waterhole.preferences.notifications') }})
@endslot
@endcomponent

