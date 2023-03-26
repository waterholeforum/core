@php
    $title = isset($link)
        ? __('waterhole::cp.edit-link-title')
        : __('waterhole::cp.create-link-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.structure')"
        :parent-title="__('waterhole::cp.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($link) ? route('waterhole.cp.structure.links.update', compact('link')) : route('waterhole.cp.structure.links.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($link)) @method('PATCH') @endif

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                @components($form->fields())
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($link) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.cp.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::cp>
