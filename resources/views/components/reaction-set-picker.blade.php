<select {{ $attributes }}>
    <option value="" @selected($value == $default?->id)>
        {{ $default?->name ?? 'None' }} (default)
    </option>

    @foreach ($reactionSets->except($default?->id) as $reactionSet)
        <option value="{{ $reactionSet->id }}" @selected($value == $reactionSet->id)>
            {{ $reactionSet->name }}
        </option>
    @endforeach
</select>
