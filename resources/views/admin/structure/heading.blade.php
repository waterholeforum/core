@php
    $title = isset($heading) ? 'Edit Heading' : 'Create a Heading';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::dialog :title="$title" class="dialog--sm">
        <form
            method="POST"
            action="{{ isset($heading) ? route('waterhole.admin.structure.headings.update', compact('heading')) : route('waterhole.admin.structure.headings.store') }}"
        >
            @csrf
            @if (isset($heading)) @method('PATCH') @endif

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <x-waterhole::field name="name" label="Name">
                    <input
                        type="text"
                        name="name"
                        id="{{ $component->id }}"
                        class="input"
                        value="{{ old('name', $heading->name ?? null) }}"
                        autofocus
                    >
                </x-waterhole::field>

                <div>
                    <div class="toolbar">
                        <button type="submit" class="btn btn--primary btn--wide">
                            {{ isset($heading) ? 'Save Changes' : 'Create' }}
                        </button>
                        <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
