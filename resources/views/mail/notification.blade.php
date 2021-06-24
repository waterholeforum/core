@component('mail::message')
{{ $html }}

@component('mail::panel')
@isset($avatar)
<img src="{{ $avatar }}" class="avatar">
@endisset
{{ $name }}:
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

