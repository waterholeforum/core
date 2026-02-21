<form method="{{ $formMethod }}" {{ $attributes }}>
    @if ($formMethod !== 'GET')
        @csrf
        @return($cancelUrl)
    @endif

    @if ($spoofMethod)
        @method($spoofMethod)
    @endif

    <div class="stack gap-lg">
        <x-waterhole::validation-errors />

        {{ $slot }}

        @if ($sections)
            @if (count($sections) > 1)
                <ui-tabs class="stack gap-lg" data-controller="tabs-deeplink">
                    <nav class="tabs scrollable scrollable-x" role="tablist">
                        @foreach ($sections as $section)
                            <a href="#{{ $section['panelId'] }}" role="tab" class="tab">
                                {{ $section['title'] }}
                            </a>
                        @endforeach
                    </nav>

                    @foreach ($sections as $section)
                        <section
                            id="{{ $section['panelId'] }}"
                            role="tabpanel"
                            {{ $resolvePanelAttributes('stack dividers card card__body') }}
                        >
                            @components($section['components'])
                        </section>
                    @endforeach
                </ui-tabs>
            @else
                <div {{ $resolvePanelAttributes('stack dividers') }}>
                    @foreach ($sections as $section)
                        @components($section['components'])
                    @endforeach
                </div>
            @endif
        @endif

        <div class="bottom-bar row gap-sm wrap text-md" data-controller="watch-sticky">
            @if (isset($actions))
                {{ $actions }}
            @else
                <button
                    type="submit"
                    {{ (new Illuminate\View\ComponentAttributeBag($submitAttributes))->class('btn bg-accent') }}
                >
                    {{ $submitLabel }}
                </button>

                <x-waterhole::cancel :default="$cancelUrl" class="btn" />
            @endif
        </div>
    </div>
</form>
