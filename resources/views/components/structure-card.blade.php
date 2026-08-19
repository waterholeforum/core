<div
    {{ $attributes->class('structure-card card card__body row align-start gap-md overlay-container') }}
>
    @icon($content->icon, ['class' => 'structure-card__icon text-xl shrink-0'])

    <div class="stack gap-xs grow">
        <h3 class="h4 weight-medium">
            <a class="has-overlay color-text" href="{{ $url }}">
                {{ $content->name }}
            </a>
        </h3>

        @if ($description)
            <span class="text-xs measure color-muted">
                {{ Waterhole\emojify(Str::limit($description, 100)) }}
            </span>
        @endif
    </div>
</div>
