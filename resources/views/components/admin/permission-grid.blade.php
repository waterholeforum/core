<div class="table-container">
    <table
        class="table permission-grid"
        data-controller="permission-grid"
    >
        <colgroup>
            <col>
            @foreach ($abilities as $ability)
                <col>
            @endforeach
        </colgroup>
        <thead>
            <tr>
                <td></td>
                @foreach ($abilities as $ability)
                    <th>{{ ucfirst($ability) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (Waterhole\Models\Group::where('id', '!=', Waterhole\Models\Group::ADMIN_ID)->get() as $group)
                <tr data-group-id="{{ $group->getKey() }}">
                    <th>
                        @if ($group->isCustom())
                            <x-waterhole::group-label :group="$group"/>
                        @else
                            {{ $group->name }}
                        @endif
                    </th>
                    @foreach ($abilities as $ability)
                        @if (($group->isGuest() && $ability !== 'view') || ($group->isMember() && $ability === 'moderate'))
                            <td></td>
                        @else
                            <td class="choice-cell">
                                <label class="choice">
                                    <input
                                        type="hidden"
                                        name="permissions[{{ $group->getMorphClass() }}:{{ $group->getKey() }}][{{ $ability }}]"
                                        value="0"
                                    >
                                    <input
                                        type="checkbox"
                                        name="permissions[{{ $group->getMorphClass() }}:{{ $group->getKey() }}][{{ $ability }}]"
                                        value="1"
                                        @if (old("permissions.{$group->getMorphClass()}:{$group->getKey()}.$ability", isset($permissions) ? $permissions->group($group)->allows($ability) : in_array($ability, $defaults))) checked @endif
                                        data-implied-by="
                                            @if ($group->isMember()) permissions[group:1][{{ $ability }}] @endif
                                            @if ($group->isCustom()) permissions[group:2][{{ $ability }}] @endif
                                        "
                                        data-depends-on="
                                            @if ($ability !== 'view') permissions[group:{{ $group->getKey() }}][view] @endif
                                        "
                                    >
                                </label>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
