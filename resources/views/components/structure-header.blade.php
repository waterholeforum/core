<div
    {{
        $attributes->class('structure-header card card__body row align-start gap-md')->merge([
            'data-channel' => $content instanceof Waterhole\Models\Channel ? $content->slug : null,
            'data-page' => $content instanceof Waterhole\Models\Page ? $content->slug : null,
            'data-shortcut-selection-key' => $content instanceof Waterhole\Models\Channel ? dom_id($content) : null,
            'data-shortcut-selection-default' => $content instanceof Waterhole\Models\Channel ? '' : null,
        ])
    }}
>
    @icon($content->icon, ['class' => 'structure-header__icon text-xxl'])

    <div class="structure-header__inner grow row wrap gap-md">
        <div class="structure-header__info grow stack gap-xs">
            <h1 class="h3" data-page-target="title">{{ $content->name }}</h1>

            @if (filled($content->description))
                <div class="content measure">
                    {{ $content->description_html }}
                </div>
            @endif
        </div>

        <div class="structure-header__controls row gap-xs justify-end">
            @if ($content instanceof Waterhole\Models\Channel)
                <x-waterhole::follow-button :followable="$content" />
            @endif

            <x-waterhole::action-menu placement="bottom-end" :for="$content" />
        </div>
    </div>
</div>
