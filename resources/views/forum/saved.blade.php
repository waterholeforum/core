<x-waterhole::layout>
    <div class="container section">
        <turbo-frame id="saved" class="card p-md">
            <div class="row gap-xs justify-between menu-sticky">
                <h1 class="menu-heading">{{ __('waterhole::forum.saved-title') }}</h1>
            </div>

            @if ($bookmarks->isNotEmpty())
                <x-waterhole::infinite-scroll :paginator="$bookmarks">
                    @foreach ($bookmarks as $bookmark)
                        <x-waterhole::saved-list-item :bookmark="$bookmark" />
                    @endforeach
                </x-waterhole::infinite-scroll>
            @else
                <div class="placeholder">
                    <p class="h4">{{ __('waterhole::forum.saved-empty-message') }}</p>
                    <p class="text-sm color-muted">
                        {!!
                            __('waterhole::forum.saved-empty-description', [
                                'icon' => svg('tabler-bookmark', 'icon')->toHtml(),
                                'action' => e(__('waterhole::forum.save-button')),
                            ])
                        !!}
                    </p>
                </div>
            @endif
        </turbo-frame>
    </div>
</x-waterhole::layout>
