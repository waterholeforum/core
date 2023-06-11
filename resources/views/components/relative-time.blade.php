<relative-time
    {{
        $attributes->merge([
            'datetime' => $dateTime->toIso8601String(),
            'tense' => 'past',
        ])
    }}
>
    {{ $dateTime->toFormattedDateString() }}
</relative-time>
