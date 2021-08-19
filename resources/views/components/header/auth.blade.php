@auth
  <ui-dropdown>
    <button>{{ Auth::user()->name }}</button>
    <ui-menu>
      <form action="{{ route('waterhole.logout') }}" method="POST">
        @csrf
        <button type="submit">Log Out</button>
      </form>
    </ui-menu>
  </ui-dropdown>
@else
  <a href="{{ route('waterhole.login') }}" class="btn btn--link">{{ __('waterhole::header.log-in') }}</a>
  <a href="{{ route('waterhole.register') }}" class="btn btn--primary">{{ __('waterhole::header.register') }}</a>
@endauth
