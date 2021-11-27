@php
    $title = isset($group) ? 'Edit Group' : 'Create a Group';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::dialog :title="$title" class="dialog--lg">
        <form
            method="POST"
            action="{{ isset($group) ? route('waterhole.admin.groups.update', compact('group')) : route('waterhole.admin.groups.store') }}"
        >
            @csrf
            @if (isset($group)) @method('PATCH') @endif

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <div class="panels">
                    <details class="panel" open>
                        <summary class="panel__header h4">Details</summary>

                        <div class="panel__body form-groups">
                            <x-waterhole::field name="name" label="Name">
                                <input
                                    type="text"
                                    name="name"
                                    id="{{ $component->id }}"
                                    class="input"
                                    value="{{ old('name', $group->name ?? null) }}"
                                    autofocus
                                >
                            </x-waterhole::field>

                            <div data-controller="reveal">
                                <div class="field__label">Appearance</div>

                                <div class="stack-lg">
                                    <div>
                                        <input type="hidden" name="is_public" value="0">
                                        <label class="choice">
                                            <input type="checkbox" data-reveal-target="if" name="is_public" value="1" @if (old('is_public', $group->is_public ?? null)) checked @endif>
                                            Show this group as a user badge
                                        </label>
                                    </div>

                                    <div class="toolbar toolbar--nowrap" data-reveal-target="then">
                                        <x-waterhole::field name="icon" label="Icon" style="flex-basis: 50%">
                                            <input
                                                type="text"
                                                name="icon"
                                                id="{{ $component->id }}"
                                                class="input"
                                                value="{{ old('icon', $group->icon ?? null) }}"
                                            >
                                        </x-waterhole::field>

                                        <x-waterhole::field name="color" label="Color" style="flex-basis: 50%">
                                            <x-waterhole::admin.color-picker
                                                name="color"
                                                id="{{ $component->id }}"
                                                value="{{ old('color', $group->color ?? null) }}"
                                            />
                                        </x-waterhole::field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="panel">
                        <summary class="panel__header h4">Permissions</summary>

                        <div class="panel__body">
                            <div class="table-container">
                                <table
                                    class="table permission-grid"
                                    data-controller="permission-grid"
                                    data-action="click->permission-grid#click mouseover->permission-grid#mouseover mouseout->permission-grid#reset"
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
                                        @foreach ($structure as $node)
                                            <tr>
                                                <th>
                                                    @if ($node->content instanceof Waterhole\Models\Channel)
                                                        <x-waterhole::channel-label
                                                            :channel="$node->content"
                                                        />
                                                    @else
                                                        {{ $node->content->name }}
                                                    @endif
                                                </th>
                                                @foreach ($abilities as $ability)
                                                    @if (method_exists($node->content, 'abilities') && in_array($ability, $node->content->abilities()))
                                                        <td class="choice-cell">
                                                            <label class="choice">
                                                                <input
                                                                    type="hidden"
                                                                    name="permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][{{ $ability }}]"
                                                                    value="{{ $node->content->permissions->member()->allows($ability) ? 1 : 0 }}"
                                                                >
                                                                <input
                                                                    type="checkbox"
                                                                    name="permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][{{ $ability }}]"
                                                                    value="1"
                                                                    @if ($node->content->permissions->member()->allows($ability)) disabled
                                                                    @endif
                                                                    @if (old("permissions.{$node->content->getMorphClass()}:{$node->content->getKey()}.$ability", $node->content->permissions->group($group ?? Waterhole\Models\Group::member())->allows($ability))) checked
                                                                    @endif
                                                                    data-depends-on="
                                                                        @if ($ability !== 'view') permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][view] @endif
                                                                        "
                                                                >
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
                    </details>
                </div>

                <div class="toolbar">
                    <button
                        type="submit"
                        class="btn btn--primary btn--wide"
                    >
                        {{ isset($group) ? 'Save Changes' : 'Create' }}
                    </button>
                    <a
                        href="{{ route('waterhole.admin.groups.index') }}"
                        class="btn"
                    >Cancel</a>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
