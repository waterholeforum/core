<div class="getting-started card stack gap-md">
    <h2 class="h4">{{ __('waterhole::admin.getting-started-title') }}</h2>

    <div class="getting-started__grid grid">
        @foreach ($items as $key => $item)
            <a
                href="{{ $item['url'] }}"
                class="row gap-md align-start block-link"
                data-turbo-frame="_top"
            >
                <x-waterhole::icon
                    :icon="$item['icon']"
                    class="text-xl no-shrink icon--thin"
                />
                <div class="stack gap-xs">
                    <div class="h5 color-accent">
                        {{ __("waterhole::admin.getting-started-$key-title") }}
                    </div>
                    <div class="color-muted text-xs">
                        {{ __("waterhole::admin.getting-started-$key-description") }}
                    </div>
                </div>
            </a>
        @endforeach

    </div>
</div>
