@php
    $title = isset($heading)
        ? __('waterhole::cp.edit-heading-title')
        : __('waterhole::cp.create-heading-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.structure')"
        :parent-title="__('waterhole::cp.structure-title')"
        :title="$title"
    />

    <x-waterhole::form
        :method="isset($heading) ? 'PATCH' : 'POST'"
        action="{{ isset($heading) ? route('waterhole.cp.structure.headings.update', compact('heading')) : route('waterhole.cp.structure.headings.store') }}"
        data-controller="dirty-form"
    >
        <div class="card">
            <div class="card__body stack dividers">
                <x-waterhole::field name="name" :label="__('waterhole::cp.heading-name-label')">
                    <input
                        type="text"
                        name="name"
                        id="{{ $component->id }}"
                        value="{{ old('name', $heading->name ?? null) }}"
                        autofocus
                    />
                </x-waterhole::field>
            </div>
        </div>
    </x-waterhole::form>
</x-waterhole::cp>
