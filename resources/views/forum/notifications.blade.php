<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="notifications">
            <div class="row gap-xs justify-between menu-sticky">
                <h1 class="menu-heading">{{ __('waterhole::notifications.title') }}</h1>

                <div class="row">
                    <form action="{{ route('waterhole.notifications.read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--icon btn--transparent">
                            <x-waterhole::icon icon="tabler-check"/>
                            <ui-tooltip>{{ __('waterhole::notifications.mark-all-as-read-button') }}</ui-tooltip>
                        </button>
                    </form>

                    <a
                        href="{{ route('waterhole.preferences.notifications') }}"
                        class="btn btn--icon btn--transparent"
                        data-turbo-frame="_top"
                    >
                        <x-waterhole::icon icon="tabler-settings"/>
                        <ui-tooltip>{{ __('waterhole::notifications.preferences-button') }}</ui-tooltip>
                    </a>
                </div>
            </div>

            @if ($notifications->isNotEmpty())
                <x-waterhole::infinite-scroll :paginator="$notifications">
                    @foreach ($notifications as $notification)
                        <x-waterhole::notification :notification="$notification"/>
                    @endforeach
                </x-waterhole::infinite-scroll>
            @else
                <div class="placeholder">
                    <x-waterhole::icon icon="tabler-bell" class="placeholder__icon"/>
                    <p class="h4">{{ __('waterhole::notifications.empty-message') }}</p>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::layout>
