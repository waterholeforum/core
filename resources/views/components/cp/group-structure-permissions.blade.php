<div class="field">
    <div class="field__label">
        {{ __('waterhole::cp.group-structure-permissions-title') }}
    </div>

    <div class="table-container card">
        <table class="table permission-grid" data-controller="permission-grid">
            <colgroup>
                <col />
                @foreach ($abilities as $ability)
                    <col />
                @endforeach
            </colgroup>

            <thead>
                <tr>
                    <td></td>
                    @foreach ($abilities as $ability)
                        <th>{{ __("waterhole::system.ability-$ability") }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($structure as $node)
                    <tr>
                        <th>
                            <div
                                class="permission-grid__structure"
                                style="--structure-depth: {{ $node->depth }}"
                            >
                                @if ($node->content instanceof Waterhole\Models\Channel)
                                    <x-waterhole::channel-label
                                        :channel="$node->content"
                                    />
                                @elseif ($node->content instanceof Waterhole\Models\StructureHeading)
                                    <span class="subtitle">
                                        {{ $node->content->name }}
                                    </span>
                                @else
                                    <span class="with-icon">
                                        @icon($node->content->icon ?? null)
                                        {{ $node->content->name }}
                                    </span>
                                @endif
                            </div>
                        </th>

                        @foreach ($abilities as $ability)
                            @if (! $node->content instanceof Waterhole\Models\StructureHeading && in_array($ability, $node->content->abilities()))
                                @php
                                    $scope = $node->content->permissionScope($ability);
                                    $key = $scope->getMorphClass() . ':' . $scope->getKey();
                                    $dependencyId = $ability === 'view' ? $node->parent_id : $node->getKey();
                                    $dependencyKey = $dependencyId ? $node->getMorphClass() . ':' . $dependencyId : null;
                                @endphp

                                <td class="choice-cell">
                                    <label class="choice">
                                        <input
                                            type="hidden"
                                            name="permissions[{{ $key }}][{{ $ability }}]"
                                            value="0"
                                        />
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $key }}][{{ $ability }}]"
                                            value="1"
                                            @disabled($inheritedGroup && $allows($inheritedGroup, $ability, $scope))
                                            @checked(old("permissions.$key.$ability", $allows($permissionGroup, $ability, $scope)))
                                            @if ($dependencyKey) data-depends-on="permissions[{{ $dependencyKey }}][view]" @endif
                                        />
                                    </label>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
