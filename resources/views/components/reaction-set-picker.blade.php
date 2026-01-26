<select {{ $attributes }}>
    <option value="default" @selected(in_array($value, [null, '', 'default']))>
        {{
            __('waterhole::cp.reaction-set-picker-default', [
                'name' => $default?->name ?? __('waterhole::cp.reaction-set-picker-none'),
            ])
        }}
    </option>

    <hr />

    @foreach ($reactionSets as $reactionSet)
        <option
            value="{{ $reactionSet->id }}"
            @selected((string) $value === (string) $reactionSet->id)
        >
            {{ $reactionSet->name }}
        </option>
    @endforeach

    @if ($reactionSets->isNotEmpty())
        <hr />
    @endif

    <option value="none" @selected($value === 'none')>
        {{ __('waterhole::cp.reaction-set-picker-none') }}
    </option>
</select>
