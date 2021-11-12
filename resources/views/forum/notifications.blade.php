<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="notifications">
            <div class="toolbar menu-sticky">
                <h1 class="menu-heading">Notifications</h1>

                <div class="spacer"></div>

                <div class="toolbar toolbar--compact">
                    <form action="{{ route('waterhole.notifications.read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--icon btn--transparent">
                            <x-waterhole::icon icon="heroicon-s-check"/>
                            <ui-tooltip>Mark All as Read</ui-tooltip>
                        </button>
                    </form>

                    <a
                        href="{{ route('waterhole.settings.notifications') }}"
                        class="btn btn--icon btn--transparent"
                        data-turbo-frame="_top"
                    >
                        <x-waterhole::icon icon="heroicon-o-cog"/>
                        <ui-tooltip>Notification Preferences</ui-tooltip>
                    </a>
                </div>
            </div>

            @if ($notifications->isNotEmpty())
                <turbo-frame id="notifications_page_{{ $notifications->currentPage() }}">
                    @foreach ($notifications as $notification)
                        <x-waterhole::notification :notification="$notification"/>
                    @endforeach

                    @if ($notifications->hasMorePages())
                        <turbo-frame
                            id="notifications_page_{{ $notifications->currentPage() + 1 }}"
                            src="{{ $notifications->nextPageUrl() }}"
                            loading="lazy"
                            class="next-page"
                            target="_top"
                        >
                            <div class="loading-indicator"></div>
                        </turbo-frame>
                    @endif
                </turbo-frame>

                <noscript>
                    {{ $notifications->links() }}
                </noscript>
            @else
                <div class="placeholder">
                    <x-waterhole::icon icon="heroicon-o-bell" class="placeholder__visual"/>
                    <p class="h3">No Notifications</p>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::layout>
