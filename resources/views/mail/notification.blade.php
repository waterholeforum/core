@component('mail::components.layout-centered.php')
  <div class="notification-text">
    @isset($avatar)
      <img src="{{ $avatar }}" class="avatar">
    @endisset
    {{ $html }}
  </div>

  @isset($actionText)
    @component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
      {{ $actionText }}
    @endcomponent
  @endisset

  @component('mail::panel')
    {!! preg_replace('/<script.*?\/script>/s', '', $excerpt) !!}
  @endcomponent

  @slot('subcopy')
    {{ $reason }}<br>
    [{{ $unsubscribeText }}]({{ $unsubscribeUrl }}) &nbsp;
    [Manage notification preferences]({{ route('settings.notifications') }})
  @endslot
@endcomponent

