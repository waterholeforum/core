<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="notifications">
            <div class="toolbar">
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

                    <button class="btn btn--icon btn--transparent">
                        <x-waterhole::icon icon="heroicon-o-cog"/>
                        <ui-tooltip>Notification Preferences</ui-tooltip>
                    </button>
                </div>
            </div>

            @forelse ($notifications as $notification)
                {{--
                    We have to opt-out of Turbo altogether for notifications,
                    because there is a bug where it does not preserve anchors
                    upon redirection: https://github.com/hotwired/turbo/issues/211
                --}}
                <a
                    href="{{ route('waterhole.notifications.show', compact('notification')) }}"
                    class="menu-item @if (! $notification->read_at) is-unread @endif"
                    role="menuitem"
                    target="_top"
                >
                    <x-waterhole::icon icon="waterhole-o-comment"/>
                    <span style="min-width: 0">
                        {{ Illuminate\Mail\Markdown::parse($notification->template->title()) }}
                        <span class="menu-item-description">
                            <x-waterhole::user-label :user="$notification->template->sender()"/> ·
                            {{ strip_tags($notification->template->excerpt()) }}
                        </span>
                    </span>
                    <span class="spacer"></span>
{{--                    <time class="text-xs color-muted" style="white-space: nowrap">2 days ago</time>--}}
                    @if ($notification->unread_count)
                        <span class="badge badge--unread" style="flex-shrink: 0">{{ $notification->unread_count }}</span>
                    @endif
                </a>
            @empty
                <div class="placeholder">
                    <x-waterhole::icon icon="heroicon-o-bell" class="placeholder__visual"/>
                    <p class="h3">No Notifications</p>
                </div>
            @endforelse
        </turbo-frame>
    </div>
</x-waterhole::layout>
