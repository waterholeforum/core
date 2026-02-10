<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="moderation" class="card p-md">
            <div class="row gap-xs justify-between menu-sticky">
                <h1 class="menu-heading">{{ __('waterhole::forum.moderation-title') }}</h1>
            </div>

            @if ($pendingFlags->isNotEmpty())
                <x-waterhole::infinite-scroll :paginator="$pendingFlags">
                    @foreach ($pendingFlags as $flag)
                        <a
                            href="{{ $flag->subject->flagUrl() }}"
                            class="menu-item notification p-sm gap-sm"
                            role="menuitem"
                        >
                            @icon(
                                $flag->subject instanceof Waterhole\Models\Post
                                    ? $flag->subject->channel->icon
                                    : 'tabler-message-circle-2',
                                ['class' => 'color-muted text-md']
                            )

                            <span class="shrink">
                                <x-waterhole::flag-summary :subject="$flag->subject" />

                                <span class="menu-item__description overflow-ellipsis">
                                    <x-waterhole::user-label :user="$flag->subject->user" />
                                    ·
                                    {{
                                        Str::limit(
                                            $flag->subject instanceof Waterhole\Models\Post
                                                ? $flag->subject->title
                                                : $flag->subject->post->title,
                                            200,
                                        )
                                    }}
                                </span>
                            </span>

                            <x-waterhole::relative-time
                                :datetime="$flag->created_at"
                                class="notification__time text-xs color-muted push-end nowrap"
                            />
                        </a>
                    @endforeach
                </x-waterhole::infinite-scroll>
            @else
                <div class="placeholder">
                    @icon('tabler-flag-check', ['class' => 'placeholder__icon'])
                    <p class="h4">{{ __('waterhole::forum.moderation-empty-message') }}</p>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::layout>
