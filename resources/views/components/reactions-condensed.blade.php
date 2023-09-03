<span
    {{
        $attributes->merge([
            'data-count' => ($count = $model->reactionsSummary->totalCount()),
            'class' => 'btn btn--sm btn--transparent is-inert reactions-condensed',
        ])
    }}
>
    <span class="row reverse">
        @foreach ($reactionTypes->take(3)->reverse() as $reactionType)
            @icon($reactionType->icon)
        @endforeach
    </span>
    {{ $count }}
</span>
