<turbo-frame id="@domid($notification)">
    {{--
        We have to opt-out of Turbo altogether for notifications,
        because there is a bug where it does not preserve anchors
        upon redirection: https://github.com/hotwired/turbo/issues/211
    --}}
    <a
        href="{{ route('waterhole.notifications.go', compact('notification')) }}"
        class="menu-item notification @if (!$notification->read_at) is-unread @endif"
        role="menuitem"
        target="_top"
    >
        @icon($notification->template->icon(), ['class' => 'color-muted text-sm'])

        <span class="shrink">
            {{ Waterhole\emojify(Illuminate\Mail\Markdown::parse($notification->template->title())) }}

            <span class="menu-item__description overflow-ellipsis">
                <x-waterhole::user-label :user="$notification->template->sender()"/> ·
                {{ Waterhole\emojify(Illuminate\Support\Str::limit(strip_tags($notification->template->excerpt()), 200)) }}
            </span>
        </span>

        <x-waterhole::time-ago
            :datetime="$notification->created_at"
            format="micro"
            class="notification__time text-xxs color-muted push-end nowrap"
        />
    </a>
</turbo-frame>
