@php
    $title = isset($group) ? 'Edit Heading' : 'Create a Heading';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::dialog :title="$title" class="dialog--md">
        <form
            method="POST"
            action="{{ isset($heading) ? route('waterhole.admin.structure.headings.update', compact('heading')) : route('waterhole.admin.structure.headings.store') }}"
        >
            @csrf
            @if (isset($group)) @method('PATCH') @endif

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

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

                <div>
                    <div class="toolbar">
                        <button type="submit" class="btn btn--primary btn--wide">
                            {{ isset($group) ? 'Save Changes' : 'Create' }}
                        </button>
                        <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
