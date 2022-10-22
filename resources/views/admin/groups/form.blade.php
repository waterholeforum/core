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
                @components($form->fields())
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
