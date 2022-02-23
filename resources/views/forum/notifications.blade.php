<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="notifications">
            <div class="row gap-xs justify-between menu-sticky">
                <h1 class="menu-heading">{{ __('waterhole::notifications.title') }}</h1>

                <div class="row">
                    <form action="{{ route('waterhole.notifications.read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--icon btn--transparent">
                            <x-waterhole::icon icon="heroicon-s-check"/>
                            <ui-tooltip>{{ __('waterhole::notifications.mark-all-as-read-button') }}</ui-tooltip>
                        </button>
                    </form>

                    <a
                        href="{{ route('waterhole.preferences.notifications') }}"
                        class="btn btn--icon btn--transparent"
                        data-turbo-frame="_top"
                    >
                        <x-waterhole::icon icon="heroicon-o-cog"/>
                        <ui-tooltip>{{ __('waterhole::notifications.preferences-button') }}</ui-tooltip>
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
                    <p class="h3">{{ __('waterhole::notifications.empty-message') }}</p>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::layout>
