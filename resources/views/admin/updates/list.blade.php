<turbo-frame id="updates_count">
    @if ($count = count($packages))
        <span class="badge badge--unread">{{ $count }}</span>
    @endif
</turbo-frame>

<turbo-frame id="updates_list">
    @if ($count)
        <div class="stack-lg">
            <div class="row gap-sm">
                <h1 class="h2">{{ $count }} Update{{ $count === 1 ? '' : 's' }} Available</h1>

                <div class="spacer"></div>

                <form action="{{ route('waterhole.admin.updates.refresh') }}" method="post" data-turbo-frame="updates_list">
                    @csrf
                    <button
                        type="submit"
                        class="btn btn--icon btn--transparent admin-updates__refresh"
                        data-updates-target="reload"
                    >
                        <x-waterhole::icon icon="heroicon-o-refresh"/>
                        <ui-tooltip>Refresh</ui-tooltip>
                    </button>
                </form>

                <form action="{{ route('waterhole.admin.updates.start') }}" data-turbo-frame="modal">
                    @foreach ($packages as $package)
                        <input type="hidden" name="packages[]" value="{{ $package['name'].(! empty($package['latest']) ? ':'.$package['latest'] : '') }}">
                    @endforeach
                    <button class="btn btn--primary">Update All</button>
                </form>
            </div>

            <ul class="card admin-structure">
                @foreach ($packages as $package)
                    <li class="admin-structure__content row gap-sm">
                        <span class="h4">{{ $package['info']['extra']['waterhole']['name'] ?? $package['name'] }}</span>

                        <small class="color-muted">
                            {{ ltrim($package['version'], 'v') }} →
                            <span class="text-unread">{{ ltrim($package['latest'], 'v') }}</span>
                        </small>

                        <span class="spacer"></span>

                        @if ($package['changelog'] ?? null)
                            <a href="{{ $package['changelog'] }}" target="_blank" class="link--sm with-icon">
                                <x-waterhole::icon icon="heroicon-o-document-text"/>
                                Changelog
                            </a>
                        @endif

                        <form action="{{ route('waterhole.admin.updates.start') }}" data-turbo-frame="modal">
                            <input type="hidden" name="packages[]" value="{{ $package['name'].':'.$package['latest'] }}">
                            <button class="btn text-xs">Update</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="placeholder">
            <x-waterhole::icon icon="heroicon-o-refresh" class="placeholder__visual"/>
            <p class="h3">No Updates Available</p>
            <form action="{{ route('waterhole.admin.updates.refresh') }}" method="post">
                @csrf
                <button
                    type="submit"
                    class="btn btn--link"
                    onclick="this.classList.add('is-loading')"
                >Check Again</button>
            </form>
        </div>
    @endif
</turbo-frame>
