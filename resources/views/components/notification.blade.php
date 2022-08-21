{{--
    We have to opt-out of Turbo altogether for notifications,
    because there is a bug where it does not preserve anchors
    upon redirection: https://github.com/hotwired/turbo/issues/211
--}}
<a
    href="{{ route('waterhole.notifications.show', compact('notification')) }}"
    class="menu-item notification @if (!$notification->read_at) is-unread @endif"
    role="menuitem"
    target="_top"
>
    <x-waterhole::icon :icon="$notification->template->icon()"/>
    <span style="min-width: 0">
        {{ Illuminate\Mail\Markdown::parse($notification->template->title()) }}
        <span class="menu-item__description">
            <x-waterhole::user-label :user="$notification->template->sender()"/> ·
            {{ strip_tags($notification->template->excerpt()) }}
        </span>
    </span>
</a>
