<x-waterhole::layout :title="__('waterhole::system.keyboard-shortcuts-title')">
    <turbo-frame id="modal">
        <x-waterhole::dialog
            :title="__('waterhole::system.keyboard-shortcuts-title')"
            class="shortcut-reference-dialog"
        >
            <x-slot name="header">
                <a
                    href="{{ Waterhole\internal_url(old('return', request('return')), route('waterhole.home')) }}"
                    class="btn btn--transparent btn--icon -m-xs push-end"
                    aria-label="{{ __('waterhole::system.close-button') }}"
                    data-action="click->modal#hide"
                    data-shortcut-trigger="navigation.close"
                >
                    @icon('tabler-x')

                    <ui-tooltip>
                        {{ __('waterhole::system.close-button') }}
                        <x-waterhole::shortcut-label shortcut="navigation.close" />
                    </ui-tooltip>
                </a>
            </x-slot>

            <div class="stack gap-lg">
                <label class="choice">
                    <input
                        type="checkbox"
                        data-keyboard-shortcuts-target="toggle"
                        data-action="change->keyboard-shortcuts#toggleEnabled"
                    />
                    {{ __('waterhole::system.keyboard-shortcuts-enabled-label') }}
                </label>

                <div class="grid gap-lg" style="--grid-min: 30ch">
                    @foreach ($shortcuts as $category => $items)
                        <section class="stack gap-sm">
                            <h2 class="subtitle">
                                {{ __('waterhole::system.keyboard-shortcuts-category-' . $category) }}
                            </h2>

                            <div class="card">
                                @foreach ($items as $shortcut)
                                    <div class="card__row row gap-sm justify-between align-center">
                                        <div>{{ $shortcut->description }}</div>
                                        <x-waterhole::shortcut-label
                                            :shortcut="$shortcut"
                                            aria-hidden="false"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::layout>
