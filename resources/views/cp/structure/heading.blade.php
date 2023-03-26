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

    <form
        method="POST"
        action="{{ isset($heading) ? route('waterhole.cp.structure.headings.update', compact('heading')) : route('waterhole.cp.structure.headings.store') }}"
        class="card card__body"
    >
        @csrf
        @if (isset($heading)) @method('PATCH') @endif

        <div class="stack dividers">
            <x-waterhole::validation-errors/>

            <x-waterhole::field name="name" :label="__('waterhole::cp.heading-name-label')">
                <input
                    type="text"
                    name="name"
                    id="{{ $component->id }}"
                    value="{{ old('name', $heading->name ?? null) }}"
                    autofocus
                >
            </x-waterhole::field>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($heading) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.cp.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::cp>
