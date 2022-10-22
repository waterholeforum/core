@php
    $title = isset($page)
        ? __('waterhole::admin.edit-page-title')
        : __('waterhole::admin.create-page-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($page) ? route('waterhole.admin.structure.pages.update', compact('page')) : route('waterhole.admin.structure.pages.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($page)) @method('PATCH') @endif

        <div class="stack gap-lg" data-controller="slugger">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                @components($form->fields())
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($heading) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.admin.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
