@php
    $title = isset($link) ? 'Edit Link' : 'Create a Link';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::dialog :title="$title" class="dialog--sm">
        <form
            method="POST"
            action="{{ isset($link) ? route('waterhole.admin.structure.links.update', compact('link')) : route('waterhole.admin.structure.links.store') }}"
        >
            @csrf
            @if (isset($link)) @method('PATCH') @endif

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <x-waterhole::field name="name" label="Name">
                    <input
                        type="text"
                        name="name"
                        id="{{ $component->id }}"
                        class="input"
                        value="{{ old('name', $link->name ?? null) }}"
                        autofocus
                    >
                </x-waterhole::field>

                <x-waterhole::field name="icon" label="Icon">
                    <input
                        type="text"
                        name="icon"
                        id="{{ $component->id }}"
                        class="input"
                        value="{{ old('icon', $link->icon ?? null) }}"
                    >
                </x-waterhole::field>

                <x-waterhole::field name="href" label="URL">
                    <input
                        type="text"
                        name="href"
                        id="{{ $component->id }}"
                        class="input"
                        value="{{ old('href', $link->href ?? null) }}"
                    >
                </x-waterhole::field>

                <div class="field">
                    <div class="field__label">Permissions</div>
                    <x-waterhole::admin.permission-grid
                        :abilities="['view']"
                        :defaults="['view']"
                        :permissions="$link->permissions ?? null"
                    />
                </div>

                <div>
                    <div class="toolbar">
                        <button type="submit" class="btn btn--primary btn--wide">
                            {{ isset($link) ? 'Save Changes' : 'Create' }}
                        </button>
                        <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
