@php
    $title = isset($group)
        ? __('waterhole::cp.edit-group-title')
        : __('waterhole::cp.create-group-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.groups.index')"
        :parent-title="__('waterhole::cp.groups-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($group) ? route('waterhole.cp.groups.update', compact('group')) : route('waterhole.cp.groups.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form"
    >
        @csrf
        @if (isset($group))
            @method('PATCH')
        @endif

        <div class="stack gap-lg">
            <x-waterhole::validation-errors />

            <div class="stack gap-md">
                @components($form->fields())
            </div>

            <div class="row gap-xs wrap">
                <button type="submit" class="btn bg-accent btn--wide">
                    {{ isset($group) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a href="{{ route('waterhole.cp.groups.index') }}" class="btn">
                    {{ __('waterhole::system.cancel-button') }}
                </a>
            </div>
        </div>
    </form>
</x-waterhole::cp>
