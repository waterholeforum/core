@php
    $title = isset($link) ? 'Edit Link' : 'Create a Link';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        parent-title="Structure"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($link) ? route('waterhole.admin.structure.links.update', compact('link')) : route('waterhole.admin.structure.links.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($link)) @method('PATCH') @endif

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
                                value="{{ old('name', $link->name ?? null) }}"
                                autofocus
                            >
                        </x-waterhole::field>

                        <x-waterhole::field name="icon" label="Icon">
                            <x-waterhole::admin.icon-picker
                                name="icon"
                                :value="old('icon', $link->icon ?? null)"
                            />
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
                    </div>
                </details>

                <details class="panel">
                    <summary class="panel__header h4">Permissions</summary>

                    <div class="panel__body form-groups">
                        <x-waterhole::admin.permission-grid
                            :abilities="['view']"
                            :defaults="['view']"
                            :permissions="$link->permissions ?? null"
                        />
                    </div>
                </details>
            </div>

            <div class="toolbar">
                <button type="submit" class="btn btn--primary btn--wide">
                    {{ isset($link) ? 'Save Changes' : 'Create' }}
                </button>
                <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
