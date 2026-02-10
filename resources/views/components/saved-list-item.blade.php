@php
    $content = $bookmark->content;
@endphp

@if ($content)
    <a
        href="{{ $content->bookmarkUrl() }}"
        class="menu-item notification p-sm gap-sm"
        role="menuitem"
        data-turbo-frame="_top"
    >
        @icon($content->bookmarkIcon(), ['class' => 'color-muted text-md'])

        <span class="shrink">
            <span class="weight-medium">{{ $content->bookmarkTitle() }}</span>

            @if ($excerpt = Str::limit((string) $content->bookmarkExcerpt(), 200))
                <span class="menu-item__description overflow-ellipsis">
                    @if ($user = $content->bookmarkUser())
                        <x-waterhole::user-label :user="$user" />
                        &middot;
                    @endif

                    <span>{{ $excerpt }}</span>
                </span>
            @endif
        </span>

        <x-waterhole::relative-time
            :datetime="$bookmark->created_at"
            class="notification__time text-xs color-muted push-end nowrap"
        />
    </a>
@endif
