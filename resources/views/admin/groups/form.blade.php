@php
    $title = isset($group)
        ? __('waterhole::admin.edit-group-title')
        : __('waterhole::admin.create-group-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.groups.index')"
        :parent-title="__('waterhole::admin.groups-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($group) ? route('waterhole.admin.groups.update', compact('group')) : route('waterhole.admin.groups.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($group)) @method('PATCH') @endif

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                <details class="card" open>
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.group-details-title') }}
                    </summary>

                    <div class="card__body form-groups">
                        <x-waterhole::field
                            name="name"
                            :label="__('waterhole::admin.group-name-label')"
                        >
                            <input
                                type="text"
                                name="name"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('name', $group->name ?? null) }}"
                                autofocus
                            >
                        </x-waterhole::field>

                        <div class="field" data-controller="reveal">
                            <div class="field__label">{{ __('waterhole::admin.group-appearance-label') }}</div>

                            <div class="stack gap-lg">
                                <div>
                                    <input type="hidden" name="is_public" value="0">
                                    <label class="choice">
                                        <input
                                            data-reveal-target="if"
                                            type="checkbox"
                                            name="is_public"
                                            value="1"
                                            @checked(old('is_public', $group->is_public ?? null))
                                        >
                                        {{ __('waterhole::admin.group-show-as-badge-label') }}
                                    </label>
                                </div>

                                <x-waterhole::field
                                    name="color"
                                    :label="__('waterhole::admin.group-color-label')"
                                    data-reveal-target="then"
                                >
                                    <x-waterhole::admin.color-picker
                                        name="color"
                                        id="{{ $component->id }}"
                                        value="{{ old('color', $group->color ?? null) }}"
                                    />
                                </x-waterhole::field>

                                <x-waterhole::field
                                    name="icon"
                                    :label="__('waterhole::admin.group-icon-label')"
                                    data-reveal-target="then"
                                >
                                    <x-waterhole::admin.icon-picker
                                        name="icon"
                                        :value="old('icon', $group->icon ?? null)"
                                    />
                                </x-waterhole::field>
                            </div>
                        </div>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h5">
                        {{ __('waterhole::admin.group-permissions-title') }}
                    </summary>

                    <div class="card__body">
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
                                            <th>{{ __("waterhole::admin.ability-$ability") }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($structure as $node)
                                        <tr>
                                            <th>
                                                @if ($node->content instanceof Waterhole\Models\Channel)
                                                    <x-waterhole::channel-label :channel="$node->content"/>
                                                @else
                                                    {{ $node->content->name }}
                                                @endif
                                            </th>
                                            @foreach ($abilities as $ability)
                                                @if (method_exists($node->content, 'abilities') && in_array($ability, $node->content->abilities()))
                                                    @php
                                                        $key = $node->content->getMorphClass().':'.$node->content->getKey();
                                                    @endphp
                                                    <td class="choice-cell">
                                                        <label class="choice">
                                                            <input
                                                                type="hidden"
                                                                name="permissions[{{ $key }}][{{ $ability }}]"
                                                                value="0"
                                                            >
                                                            <input
                                                                type="checkbox"
                                                                name="permissions[{{ $key }}][{{ $ability }}]"
                                                                value="1"
                                                                {{--
                                                                    If members are allowed, then this group *must* be allowed too,
                                                                    so disable the checkbox.
                                                                --}}
                                                                @disabled(Waterhole::permissions()->member()->allows($ability, $node->content))
                                                                {{--
                                                                    Check this box if it was checked before, or if the ability is
                                                                    allowed for this group, or for members in general.
                                                                --}}
                                                                @checked(old("permissions.$key.$ability", Waterhole::permissions()->group($group ?? Waterhole\Models\Group::member())->allows($ability, $node->content)))
                                                                {{--
                                                                    Non-"view" abilities depend on the "view" ability being allowed.
                                                                --}}
                                                                @if ($ability !== 'view') data-depends-on="permissions[{{ $key }}][view]" @endif
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

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($group) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.admin.groups.index') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
