@php
    $title = isset($heading)
        ? __('waterhole::admin.edit-heading-title')
        : __('waterhole::admin.create-heading-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($heading) ? route('waterhole.admin.structure.headings.update', compact('heading')) : route('waterhole.admin.structure.headings.store') }}"
        class="card"
    >
        @csrf
        @if (isset($heading)) @method('PATCH') @endif

        <div class="stack-lg">
            <x-waterhole::validation-errors/>

            <x-waterhole::field name="name" :label="__('waterhole::admin.heading-name-label')">
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
                <div class="row gap-md">
                    <button
                        type="submit"
                        class="btn btn--primary btn--wide"
                    >
                        {{ isset($heading) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                    </button>

                    <a
                        href="{{ route('waterhole.admin.structure') }}"
                        class="btn"
                    >{{ __('waterhole::system.cancel-button') }}</a>
                </div>
            </div>
        </div>
    </form>
</x-waterhole::admin>
