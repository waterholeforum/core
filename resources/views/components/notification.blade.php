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
        <x-waterhole::icon :icon="$notification->template->icon()" class="color-muted text-sm"/>

        <span class="shrink">
            {{ Waterhole\emojify(Illuminate\Mail\Markdown::parse($notification->template->title())) }}

            <span class="menu-item__description overflow-ellipsis">
                <x-waterhole::user-label :user="$notification->template->sender()"/> ·
                {{ Waterhole\emojify(strip_tags($notification->template->excerpt())) }}
            </span>
        </span>

        <x-waterhole::time-ago :datetime="$notification->created_at" format="micro" class="text-xxs push-end"/>
    </a>
</turbo-frame>
