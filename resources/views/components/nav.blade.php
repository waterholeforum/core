<nav aria-labelledby="nav-title">
  <h2 id="nav-title">Forum Navigation</h2>

  <a href="{{ route('waterhole.home') }}">
    {{ __('waterhole::forum.home') }}
  </a>

  @php $channels = \Waterhole\Models\Channel::all() @endphp
  @foreach (config('waterhole.nav.tree', []) as $group)
    @if (! empty($group['title']))
      <h3>{{ $group['title'] }}</h3>
    @endif
    <ul>
      @foreach ($group['children'] as $child)
        @if (isset($child['channel']) && $channel = $channels->find($child['channel']))
          <li><a href="{{ $channel->url }}">{{ $channel->display_name }}</a></li>
        @elseif (isset($child['url']))
        <li><a href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
        @endif
      @endforeach
    </ul>

  @endforeach
</nav>
