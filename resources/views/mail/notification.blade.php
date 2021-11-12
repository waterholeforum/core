@component('mail::message')
{{ $html }}

@component('mail::panel')
@isset($avatar)
<img src="{{ $avatar }}" class="avatar">
@endisset
{{ $name }}:
{!! preg_replace('/<script.*?\/script>/s', '', $excerpt) !!}
@endcomponent


@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionText }}
@endcomponent
@endisset

@slot('subcopy')
{{ $reason }}<br>
[{{ $unsubscribeText }}]({{ $unsubscribeUrl }}) &nbsp;
[Manage notification preferences]({{ route('waterhole.settings.notifications') }})
@endslot
@endcomponent

