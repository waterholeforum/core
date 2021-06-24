<time-ago datetime="{{ $dateTime->toIso8601String() }}" {{ $attributes }}>
    {{ $dateTime->toFormattedDateString() }}
</time-ago>
