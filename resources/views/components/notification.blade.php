<turbo-frame id="@domid($notification)">
    {{--
        We have to opt-out of Turbo altogether for notifications,
        because there is a bug where it does not preserve anchors
        upon redirection: https://github.com/hotwired/turbo/issues/211
    --}}
    <a
        href="{{ route('waterhole.notifications.go', compact('notification')) }}"
        class="menu-item notification p-sm gap-sm @if (!$notification->read_at) is-unread @endif"
        role="menuitem"
        target="_top"
    >
        @icon($notification->template->icon(), ['class' => 'color-muted text-md'])

        <span class="shrink">
            {{ $notification->template->title() }}

            <span class="menu-item__description overflow-ellipsis">
                <x-waterhole::user-label :user="$notification->template->sender()" />
                ·
                {{ Str::limit(strip_tags($notification->template->excerpt()), 200) }}
            </span>
        </span>

        <x-waterhole::relative-time
            :datetime="$notification->created_at"
            class="notification__time text-xs color-muted push-end nowrap"
        />
    </a>
</turbo-frame>
